<article class="admin-card editor-panel" id="faq-editor-panel" data-module-panel @if($activeModule !== 'faq') hidden @endif>
    <div class="editor-header">
        <div class="editor-copy">
            <p class="section-kicker">FAQ Management</p>
            <h2>Frequently Asked Questions</h2>
            <p class="admin-subtitle">Newest questions appear automatically at the top. Add, edit, or delete questions and answers displayed on the public FAQ page.</p>
        </div>

        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <span class="editor-status" data-faq-active-count-badge>
                {{ $faqs->where('is_active', true)->count() }} Active (Total {{ $faqsTotal ?? $faqs->count() }})
            </span>
            <button class="submit-button" type="button" data-faq-toggle-create style="padding: 9px 18px; font-size: 0.85rem; font-weight: 600; border-radius: 999px; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Add FAQ</span>
            </button>
        </div>
    </div>

    {{-- Create New FAQ Section --}}
    <div class="faq-create-card-container" id="faq-create-form-wrap" style="{{ ($errors->any() && old('_form_action') === 'store') ? '' : 'display: none;' }}; margin-bottom: 24px; padding: 24px; border: 1.5px dashed rgba(12, 80, 93, 0.35); border-radius: 14px; background: rgba(12, 80, 93, 0.03);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #0c505d; display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                Create New FAQ
            </h3>
            <button type="button" data-faq-toggle-create style="background: none; border: none; font-size: 0.85rem; color: #64748b; cursor: pointer; text-decoration: underline;">Cancel</button>
        </div>

        <form action="{{ route('admin.content.faqs.store') }}" method="post">
            @csrf
            <input type="hidden" name="_form_action" value="store">

            <div style="margin-bottom: 16px;">
                <label class="toggle-bar" style="margin: 0; padding: 10px 16px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px;">
                    <span class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                        <span class="toggle-track" aria-hidden="true"><span class="toggle-thumb"></span></span>
                    </span>
                    <span class="toggle-copy">
                        <span class="toggle-title" style="font-size: 0.88rem;">Visible on public FAQ page</span>
                        <span class="toggle-meta" style="font-size: 0.78rem;">Turn off to keep this question as an internal draft</span>
                    </span>
                </label>
            </div>

            <div class="field-group" style="margin-bottom: 16px;">
                <label class="field-label">Question <span style="color: #e53935;">*</span></label>
                <input class="field-input" type="text" name="question" value="{{ old('question') }}" placeholder="e.g. What is King Lotus International hotel share ownership?" required maxlength="500">
                @if(old('_form_action') === 'store')
                    @error('question')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                @endif
            </div>

            <div class="field-group" style="margin-bottom: 20px;">
                <label class="field-label">Answer <span style="color: #e53935;">*</span></label>
                <textarea class="field-textarea" name="answer" rows="4" placeholder="Provide a detailed, clear answer..." required maxlength="5000" style="min-height: 100px;">{{ old('answer') }}</textarea>
                @if(old('_form_action') === 'store')
                    @error('answer')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                @endif
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="project-editor-remove" data-faq-toggle-create style="padding: 9px 18px;">Cancel</button>
                <button type="submit" class="submit-button" style="padding: 9px 22px;">Save FAQ</button>
            </div>
        </form>
    </div>

    {{-- Existing FAQs list with Scroll Loading Pagination --}}
    <div class="faq-list-container" data-faq-list data-current-page="1" data-has-more="{{ ($faqsHasMore ?? false) ? 'true' : 'false' }}" data-items-url="{{ route('admin.content.faqs.items') }}" style="display: flex; flex-direction: column; gap: 14px;">
        @forelse ($faqs as $faq)
            <div class="project-editor-card faq-card-item" id="faq-card-{{ $faq->id }}" data-faq-card-id="{{ $faq->id }}" style="margin: 0; padding: 18px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background: #ffffff; transition: box-shadow 0.2s ease;">
                {{-- Header row --}}
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 260px;">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(12, 80, 93, 0.08); color: #0c505d; font-size: 0.85rem; font-weight: 700; flex-shrink: 0;">
                            Q
                        </span>
                        <div>
                            <h4 style="margin: 0 0 4px 0; font-size: 0.98rem; font-weight: 600; color: #0f172a; line-height: 1.35;">
                                {{ $faq->question }}
                            </h4>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <span style="font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 999px; {{ $faq->is_active ? 'background: #dcfce7; color: #15803d;' : 'background: #f1f5f9; color: #64748b;' }}">
                                    {{ $faq->is_active ? 'Visible' : 'Hidden' }}
                                </span>
                                <span style="font-size: 0.75rem; color: #94a3b8;">
                                    Updated {{ $faq->updated_at?->diffForHumans() ?? 'recently' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <button type="button" class="leadership-btn-toggle" data-faq-toggle-edit="{{ $faq->id }}" aria-label="Edit FAQ">
                            <span class="leadership-btn-toggle-label">Edit</span>
                        </button>
                        <form action="{{ route('admin.content.faqs.destroy', $faq) }}" method="post" style="display: inline;" data-faq-delete-form>
                            @csrf
                            @method('delete')
                            <button type="submit" class="project-editor-remove" style="padding: 7px 14px;" data-confirm-message="Are you sure you want to delete this FAQ question?">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Collapsible Edit Form --}}
                <div id="faq-edit-form-{{ $faq->id }}" style="display: none; margin-top: 18px; padding-top: 18px; border-top: 1px dashed #e2e8f0;">
                    <form action="{{ route('admin.content.faqs.update', $faq) }}" method="post">
                        @csrf
                        @method('patch')

                        <div style="margin-bottom: 14px;">
                            <label class="toggle-bar" style="margin: 0; padding: 8px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <span class="toggle-switch">
                                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $faq->is_active))>
                                    <span class="toggle-track" aria-hidden="true"><span class="toggle-thumb"></span></span>
                                </span>
                                <span class="toggle-copy">
                                    <span class="toggle-title" style="font-size: 0.85rem;">Visible on public FAQ page</span>
                                </span>
                            </label>
                        </div>

                        <div class="field-group" style="margin-bottom: 14px;">
                            <label class="field-label" style="font-size: 0.82rem;">Question <span style="color: #e53935;">*</span></label>
                            <input class="field-input" type="text" name="question" value="{{ old('question', $faq->question) }}" required maxlength="500">
                        </div>

                        <div class="field-group" style="margin-bottom: 16px;">
                            <label class="field-label" style="font-size: 0.82rem;">Answer <span style="color: #e53935;">*</span></label>
                            <textarea class="field-textarea" name="answer" rows="4" required maxlength="5000" style="min-height: 90px;">{{ old('answer', $faq->answer) }}</textarea>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 10px;">
                            <button type="button" class="project-editor-remove" data-faq-toggle-edit="{{ $faq->id }}" style="padding: 7px 16px;">Cancel</button>
                            <button type="submit" class="submit-button" style="padding: 7px 20px;">Update FAQ</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div id="faq-empty-state" style="text-align: center; padding: 48px 24px; background: #f8fafc; border-radius: 12px; border: 1.5px dashed #cbd5e1;">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 12px auto; display: block;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <h4 style="margin: 0 0 6px 0; color: #334155; font-size: 1rem; font-weight: 600;">No FAQs found yet</h4>
                <p style="margin: 0 0 16px 0; color: #64748b; font-size: 0.88rem;">Start by adding your first FAQ question and answer for visitors.</p>
                <button type="button" class="submit-button" data-faq-toggle-create style="padding: 8px 18px; font-size: 0.85rem;">+ Add First FAQ</button>
            </div>
        @endforelse
    </div>

    {{-- Infinite Scroll Sentinel for Admin --}}
    <div data-faq-sentinel style="min-height: 20px; display: {{ ($faqsHasMore ?? false) ? 'flex' : 'none' }}; justify-content: center; align-items: center; margin: 16px 0;">
        <div data-faq-loader style="display: none; align-items: center; gap: 8px; color: #0c505d; font-size: 0.85rem; font-weight: 500;">
            <span class="upload-spinner" aria-hidden="true" style="width: 16px; height: 16px; border: 2px solid rgba(12, 80, 93, 0.2); border-top-color: #0c505d; border-radius: 50%; display: inline-block; animation: spin 0.8s linear infinite;"></span>
            <span>Loading more FAQs...</span>
        </div>
    </div>

    {{-- Card Template for Dynamically Loaded FAQs --}}
    <template id="faq-card-template">
        <div class="project-editor-card faq-card-item" data-faq-card-id="__ID__" style="margin: 0; padding: 18px 20px; border-radius: 12px; border: 1px solid #e2e8f0; background: #ffffff; transition: box-shadow 0.2s ease;">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 14px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 260px;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(12, 80, 93, 0.08); color: #0c505d; font-size: 0.85rem; font-weight: 700; flex-shrink: 0;">
                        Q
                    </span>
                    <div>
                        <h4 data-faq-title style="margin: 0 0 4px 0; font-size: 0.98rem; font-weight: 600; color: #0f172a; line-height: 1.35;">
                            __QUESTION__
                        </h4>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <span data-faq-status-badge style="font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 999px;">
                                __STATUS__
                            </span>
                            <span data-faq-updated style="font-size: 0.75rem; color: #94a3b8;">
                                __UPDATED__
                            </span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 8px;">
                    <button type="button" class="leadership-btn-toggle" data-faq-toggle-edit="__ID__" aria-label="Edit FAQ">
                        <span class="leadership-btn-toggle-label">Edit</span>
                    </button>
                    <form action="__DESTROY_URL__" method="post" style="display: inline;" data-faq-delete-form>
                        @csrf
                        @method('delete')
                        <button type="submit" class="project-editor-remove" style="padding: 7px 14px;" data-confirm-message="Are you sure you want to delete this FAQ question?">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <div id="faq-edit-form-__ID__" style="display: none; margin-top: 18px; padding-top: 18px; border-top: 1px dashed #e2e8f0;">
                <form action="__UPDATE_URL__" method="post">
                    @csrf
                    @method('patch')

                    <div style="margin-bottom: 14px;">
                        <label class="toggle-bar" style="margin: 0; padding: 8px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                            <span class="toggle-switch">
                                <input type="checkbox" name="is_active" value="1" data-faq-active-input>
                                <span class="toggle-track" aria-hidden="true"><span class="toggle-thumb"></span></span>
                            </span>
                            <span class="toggle-copy">
                                <span class="toggle-title" style="font-size: 0.85rem;">Visible on public FAQ page</span>
                            </span>
                        </label>
                    </div>

                    <div class="field-group" style="margin-bottom: 14px;">
                        <label class="field-label" style="font-size: 0.82rem;">Question <span style="color: #e53935;">*</span></label>
                        <input class="field-input" type="text" name="question" data-faq-question-input required maxlength="500">
                    </div>

                    <div class="field-group" style="margin-bottom: 16px;">
                        <label class="field-label" style="font-size: 0.82rem;">Answer <span style="color: #e53935;">*</span></label>
                        <textarea class="field-textarea" name="answer" data-faq-answer-input rows="4" required maxlength="5000" style="min-height: 90px;"></textarea>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 10px;">
                        <button type="button" class="project-editor-remove" data-faq-toggle-edit="__ID__" style="padding: 7px 16px;">Cancel</button>
                        <button type="submit" class="submit-button" style="padding: 7px 20px;">Update FAQ</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</article>
