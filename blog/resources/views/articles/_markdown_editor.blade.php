@php
    $name = $name ?? "body";
    $value = $value ?? "";
@endphp

<div class="markdown-editor mb-2" data-markdown-editor>
    <div class="btn-toolbar mb-2" role="toolbar" aria-label="Markdown toolbar">
        <div class="btn-group btn-group-sm me-2" role="group">
            <button type="button" class="btn btn-outline-secondary" data-md-action="heading" title="Heading">#</button>
            <button type="button" class="btn btn-outline-secondary fw-bold" data-md-action="bold" title="Bold">B</button>
            <button type="button" class="btn btn-outline-secondary fst-italic" data-md-action="italic" title="Italic">I</button>
            <button type="button" class="btn btn-outline-secondary font-monospace" data-md-action="code" title="Code">&lt;&gt;</button>
        </div>
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-secondary" data-md-action="quote" title="Quote">"</button>
            <button type="button" class="btn btn-outline-secondary" data-md-action="list" title="List">-</button>
            <button type="button" class="btn btn-outline-secondary" data-md-action="link" title="Link">Link</button>
        </div>
    </div>

    <textarea name="{{ $name }}" placeholder="body" class="form-control mb-2 markdown-input" rows="10">{{ $value }}</textarea>

    <div class="markdown-preview border rounded p-3 bg-light">
        <div class="text-muted small mb-2">Preview</div>
        <div class="markdown-body" data-md-preview></div>
    </div>
</div>

@once
    @push("scripts")
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const escapeHtml = (value) => value
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");

                const inlineMarkdown = (value) => escapeHtml(value)
                    .replace(/`([^`]+)`/g, "<code>$1</code>")
                    .replace(/\*\*([^*]+)\*\*/g, "<strong>$1</strong>")
                    .replace(/\*([^*]+)\*/g, "<em>$1</em>")
                    .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2">$1</a>');

                const renderMarkdown = (value) => {
                    const lines = value.split(/\r?\n/);
                    let html = "";
                    let inList = false;

                    lines.forEach((line) => {
                        if (/^\s*-\s+/.test(line)) {
                            if (!inList) {
                                html += "<ul>";
                                inList = true;
                            }

                            html += `<li>${inlineMarkdown(line.replace(/^\s*-\s+/, ""))}</li>`;
                            return;
                        }

                        if (inList) {
                            html += "</ul>";
                            inList = false;
                        }

                        if (/^###\s+/.test(line)) {
                            html += `<h3>${inlineMarkdown(line.replace(/^###\s+/, ""))}</h3>`;
                        } else if (/^##\s+/.test(line)) {
                            html += `<h2>${inlineMarkdown(line.replace(/^##\s+/, ""))}</h2>`;
                        } else if (/^#\s+/.test(line)) {
                            html += `<h1>${inlineMarkdown(line.replace(/^#\s+/, ""))}</h1>`;
                        } else if (/^>\s+/.test(line)) {
                            html += `<blockquote>${inlineMarkdown(line.replace(/^>\s+/, ""))}</blockquote>`;
                        } else if (line.trim()) {
                            html += `<p>${inlineMarkdown(line)}</p>`;
                        }
                    });

                    if (inList) {
                        html += "</ul>";
                    }

                    return html || '<p class="text-muted mb-0">Nothing to preview.</p>';
                };

                const insertMarkdown = (textarea, action) => {
                    const start = textarea.selectionStart;
                    const end = textarea.selectionEnd;
                    const selected = textarea.value.slice(start, end);
                    const before = textarea.value.slice(0, start);
                    const after = textarea.value.slice(end);
                    const fallback = selected || "text";
                    const actions = {
                        heading: `## ${fallback}`,
                        bold: `**${fallback}**`,
                        italic: `*${fallback}*`,
                        code: `\`${fallback}\``,
                        quote: `> ${fallback}`,
                        list: `- ${fallback}`,
                        link: `[${fallback}](https://example.com)`,
                    };
                    const next = actions[action] || fallback;

                    textarea.value = `${before}${next}${after}`;
                    textarea.focus();
                    textarea.setSelectionRange(start, start + next.length);
                    textarea.dispatchEvent(new Event("input", { bubbles: true }));
                };

                document.querySelectorAll("[data-markdown-editor]").forEach((editor) => {
                    const textarea = editor.querySelector(".markdown-input");
                    const preview = editor.querySelector("[data-md-preview]");
                    const updatePreview = () => preview.innerHTML = renderMarkdown(textarea.value);

                    editor.querySelectorAll("[data-md-action]").forEach((button) => {
                        button.addEventListener("click", () => insertMarkdown(textarea, button.dataset.mdAction));
                    });

                    textarea.addEventListener("input", updatePreview);
                    updatePreview();
                });
            });
        </script>
    @endpush
@endonce
