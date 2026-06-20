import { chromium } from 'playwright';

const BASE = 'http://127.0.0.1:8000';
const results = [];

function pass(name, detail = '') {
  results.push({ status: 'PASS', name, detail });
  console.log(`✓ ${name}${detail ? ` — ${detail}` : ''}`);
}

function fail(name, detail = '') {
  results.push({ status: 'FAIL', name, detail });
  console.error(`✗ ${name}${detail ? ` — ${detail}` : ''}`);
}

async function login(page, email, password) {
  await page.goto(`${BASE}/login`);
  await page.fill('#email', email);
  await page.fill('#password', password);
  await page.click('button[type="submit"]');
  await page.waitForURL((url) => !url.pathname.includes('/login'));
}

const browser = await chromium.launch({ headless: true });

try {
  // --- Guest tests (fresh context) ---
  const guest = await browser.newContext();
  const guestPage = await guest.newPage();

  await guestPage.goto(BASE);
  const titles = await guestPage.locator('article h3 a').allTextContents();
  if (titles.length === 3) pass('Guest home lists 3 posts', titles.join(', '));
  else fail('Guest home lists 3 posts', `found ${titles.length}`);

  await guestPage.goto(`${BASE}/posts/1`);
  const postTitle = await guestPage.locator('header h2').textContent();
  if (postTitle?.includes('Getting Started with Laravel 12')) pass('Guest can view post detail');
  else fail('Guest can view post detail', postTitle ?? 'no title');

  if (await guestPage.locator('text=Great intro').count()) pass('Post shows seeded comment from Bob');
  else fail('Post shows seeded comment from Bob');

  await guestPage.goto(`${BASE}/categories`);
  const categoryNames = await guestPage.locator('tbody td:first-child a').allTextContents();
  if (categoryNames.sort().join(',') === 'Lifestyle,Technology,Travel') pass('Guest can view categories');
  else fail('Guest can view categories', categoryNames.join(', '));

  await guestPage.goto(`${BASE}/posts/create`);
  if (guestPage.url().includes('/login')) pass('Guest redirected to login for create post');
  else fail('Guest redirected to login for create post', guestPage.url());

  await guest.close();

  // --- Alice tests (fresh context, no intended redirect) ---
  const aliceCtx = await browser.newContext();
  const alice = await aliceCtx.newPage();

  await login(alice, 'alice@example.com', 'password');
  pass('Alice can log in');

  await alice.goto(BASE);
  if (await alice.locator('a', { hasText: 'New Post' }).count()) pass('Alice sees New Post button');
  else fail('Alice sees New Post button');

  await alice.goto(`${BASE}/posts/1`);
  if (await alice.locator('header a', { hasText: 'Edit' }).count()) pass('Alice sees Edit on her own post');
  else fail('Alice sees Edit on her own post');

  await alice.goto(`${BASE}/posts/2`);
  if (!(await alice.locator('header a', { hasText: 'Edit' }).count())) pass('Alice cannot edit Bob\'s post');
  else fail('Alice cannot edit Bob\'s post');

  await alice.fill('#content', 'Browser test comment from Alice');
  const commentResponse = alice.waitForResponse(
    (r) => r.url().includes('/comments') && r.request().method() === 'POST',
  );
  await alice.getByRole('button', { name: 'Post Comment' }).click();
  const response = await commentResponse;
  if (response.status() === 302 && (await alice.locator('text=Browser test comment from Alice').count())) {
    pass('Alice can add comment on Bob\'s post');
  } else {
    fail('Alice can add comment on Bob\'s post', `status ${response.status()}`);
  }

  await alice.locator('nav button').first().click();
  await alice.getByRole('link', { name: 'Log Out' }).first().click();
  await alice.waitForURL((url) => url.pathname === '/' || url.pathname === '/posts');
  pass('Alice can log out');

  await aliceCtx.close();

  // --- Bob tests ---
  const bobCtx = await browser.newContext();
  const bob = await bobCtx.newPage();

  await login(bob, 'bob@example.com', 'password');
  pass('Bob can log in');

  await bob.goto(`${BASE}/posts/2`);
  if (await bob.locator('header a', { hasText: 'Edit' }).count()) pass('Bob sees Edit on his own post');
  else fail('Bob sees Edit on his own post');

  await bob.goto(`${BASE}/categories`);
  if (await bob.locator('a', { hasText: 'New Category' }).count()) pass('Bob sees New Category button');
  else fail('Bob sees New Category button');

  await bobCtx.close();
} catch (error) {
  fail('Unexpected error', error.message);
} finally {
  await browser.close();
}

const failed = results.filter((r) => r.status === 'FAIL');
console.log('\n---');
console.log(`${results.length - failed.length}/${results.length} passed`);
process.exit(failed.length ? 1 : 0);
