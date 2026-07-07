<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use App\Models\FooterSetting;
use App\Models\GallerySection;
use App\Models\LeadershipSection;
use App\Models\ProjectSection;
use App\Models\ShareholderReviewSection;
use App\Models\SiteNotice;
use App\Models\ValuedShareholderSection;
use App\Models\WhySection;
use App\Support\PublicWebpUploader;
use App\Support\RichTextSanitizer;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;

class ContentManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.content.index', [
            'admin' => Auth::guard('admin')->user(),
            'notice' => SiteNotice::query()->latest('updated_at')->first(),
            'aboutSection' => AboutSection::query()->first(),
            'whySection' => WhySection::query()->first(),
            'projectSection' => ProjectSection::query()->first(),
            'gallerySection' => GallerySection::query()->first(),
            'shareholderReviewSection' => ShareholderReviewSection::query()->first(),
            'leadershipSection' => LeadershipSection::query()->first(),
            'valuedShareholderSection' => ValuedShareholderSection::query()->first(),
            'footerSetting' => FooterSetting::query()->first(),
        ]);
    }

    public function updateNotice(Request $request, PublicWebpUploader $uploader): RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'hero_background_path' => ['nullable', 'string', 'max:2048'],
            'hero_background' => ['nullable', 'image', 'max:6144'],
        ]);

        $notice = SiteNotice::query()->firstOrNew();
        $notice->message = $validated['message'];
        $notice->is_active = $request->boolean('is_active');

        $heroBackgroundPath = $this->sanitizeManagedUploadPath(
            $validated['hero_background_path'] ?? $notice->hero_background_path,
            'uploads/hero',
        ) ?? $this->sanitizeManagedUploadPath($notice->hero_background_path, 'uploads/hero');

        try {
            if ($request->hasFile('hero_background')) {
                $heroBackgroundPath = $uploader->store(
                    $request->file('hero_background'),
                    'uploads/hero',
                    $heroBackgroundPath,
                );
            }
        } catch (RuntimeException $exception) {
            throw ValidationException::withMessages([
                'hero_background' => 'Hero background upload failed. Please try another image.',
            ]);
        }

        $notice->hero_background_path = $heroBackgroundPath;
        $notice->save();
        SiteCache::forgetPublicPages();

        return back()->with('success', 'Notice updated successfully.');
    }

    public function updateAbout(Request $request, PublicWebpUploader $uploader): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:220'],
            'description' => ['required', 'string', 'max:2000'],
            'left_video_url' => ['nullable', 'url', 'max:2048'],
            'right_video_url' => ['nullable', 'url', 'max:2048'],
            'left_thumbnail' => ['nullable', 'image', 'max:6144'],
            'right_thumbnail' => ['nullable', 'image', 'max:6144'],
        ]);

        $this->assertYoutubeUrl($validated['left_video_url'] ?? null, 'left_video_url');
        $this->assertYoutubeUrl($validated['right_video_url'] ?? null, 'right_video_url');

        $aboutSection = AboutSection::query()->firstOrNew();
        $aboutSection->title = $validated['title'];
        $aboutSection->subtitle = $validated['subtitle'] ?? null;
        $aboutSection->description = $validated['description'];
        $aboutSection->left_video_url = $validated['left_video_url'] ?? null;
        $aboutSection->right_video_url = $validated['right_video_url'] ?? null;

        try {
            if ($request->hasFile('left_thumbnail')) {
                $aboutSection->left_thumbnail_path = $uploader->store(
                    $request->file('left_thumbnail'),
                    'uploads/about',
                    $aboutSection->left_thumbnail_path,
                );
            }
        } catch (RuntimeException $exception) {
            throw ValidationException::withMessages([
                'left_thumbnail' => 'Left thumbnail upload failed. Please try another image.',
            ]);
        }

        try {
            if ($request->hasFile('right_thumbnail')) {
                $aboutSection->right_thumbnail_path = $uploader->store(
                    $request->file('right_thumbnail'),
                    'uploads/about',
                    $aboutSection->right_thumbnail_path,
                );
            }
        } catch (RuntimeException $exception) {
            throw ValidationException::withMessages([
                'right_thumbnail' => 'Right thumbnail upload failed. Please try another image.',
            ]);
        }

        $aboutSection->save();
        SiteCache::forgetPublicPages();

        return back()->with('success', 'About section updated successfully.');
    }

    public function updateFooter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'youtube_url' => ['nullable', 'url', 'max:2048'],
            'facebook_url' => ['nullable', 'url', 'max:2048'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:500'],
            'location_title' => ['nullable', 'string', 'max:180'],
            'location_subtitle' => ['nullable', 'string', 'max:255'],
            'location_map_url' => ['nullable', 'url', 'max:2048'],
            'office_section_title' => ['nullable', 'string', 'max:180'],
            'office_section_subtitle' => ['nullable', 'string', 'max:255'],
            'office_cards' => ['nullable', 'array'],
            'office_cards.*.name' => ['nullable', 'string', 'max:180'],
            'office_cards.*.address' => ['nullable', 'string', 'max:1200'],
            'office_cards.*.map_url' => ['nullable', 'url', 'max:2048'],
            'office_cards.*.phone' => ['nullable', 'string', 'max:60'],
            'office_cards.*.email' => ['nullable', 'email', 'max:255'],
            'terms_title' => ['nullable', 'string', 'max:180'],
            'terms_subtitle' => ['nullable', 'string', 'max:255'],
            'terms_content' => ['nullable', 'string', 'max:20000'],
        ]);

        $officeCards = collect($validated['office_cards'] ?? [])
            ->map(function ($office): ?array {
                $data = is_array($office) ? $office : [];

                $normalized = [
                    'name' => trim((string) ($data['name'] ?? '')),
                    'address' => trim((string) ($data['address'] ?? '')),
                    'map_url' => trim((string) ($data['map_url'] ?? '')),
                    'phone' => trim((string) ($data['phone'] ?? '')),
                    'email' => trim((string) ($data['email'] ?? '')),
                ];

                if (
                    ! filled($normalized['name'])
                    && ! filled($normalized['address'])
                    && ! filled($normalized['map_url'])
                    && ! filled($normalized['phone'])
                    && ! filled($normalized['email'])
                ) {
                    return null;
                }

                return $normalized;
            })
            ->filter()
            ->values()
            ->all();

        $footerSetting = FooterSetting::query()->firstOrNew();
        $footerSetting->youtube_url = $validated['youtube_url'] ?? null;
        $footerSetting->facebook_url = $validated['facebook_url'] ?? null;
        $footerSetting->contact_email = $validated['contact_email'] ?? null;
        $footerSetting->contact_phone = filled($validated['contact_phone'] ?? null)
            ? trim((string) $validated['contact_phone'])
            : null;
        $footerSetting->location_title = filled($validated['location_title'] ?? null)
            ? trim((string) $validated['location_title'])
            : null;
        $footerSetting->location_subtitle = filled($validated['location_subtitle'] ?? null)
            ? trim((string) $validated['location_subtitle'])
            : null;
        $footerSetting->location_map_url = filled($validated['location_map_url'] ?? null)
            ? trim((string) $validated['location_map_url'])
            : null;
        $footerSetting->office_section_title = filled($validated['office_section_title'] ?? null)
            ? trim((string) $validated['office_section_title'])
            : null;
        $footerSetting->office_section_subtitle = filled($validated['office_section_subtitle'] ?? null)
            ? trim((string) $validated['office_section_subtitle'])
            : null;
        $footerSetting->office_cards = $officeCards;
        $footerSetting->office_name = $officeCards[0]['name'] ?? null;
        $footerSetting->office_address = $officeCards[0]['address'] ?? null;
        $footerSetting->office_map_url = $officeCards[0]['map_url'] ?? null;
        $footerSetting->terms_title = $validated['terms_title'] ?? null;
        $footerSetting->terms_subtitle = $validated['terms_subtitle'] ?? null;
        $footerSetting->terms_intro = null;
        $footerSetting->terms_content = $this->sanitizeRichText($validated['terms_content'] ?? null);
        $footerSetting->save();
        SiteCache::forgetPublicPages();

        return back()->with('success', 'Footer, location, and legal content updated successfully.');
    }

    public function updateWhy(Request $request, PublicWebpUploader $uploader): RedirectResponse
    {
        $validated = $request->validate([
            'why_title' => ['required', 'string', 'max:180'],
            'why_description' => ['required', 'string', 'max:2000'],
            'feature_points' => ['nullable', 'string', 'max:2500'],
            'cta_label' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'url', 'max:2048'],
            'video_url' => ['nullable', 'url', 'max:2048'],
            'thumbnail' => ['nullable', 'image', 'max:6144'],
        ]);

        $this->assertYoutubeUrl($validated['video_url'] ?? null, 'video_url');

        $whySection = WhySection::query()->firstOrNew();
        $whySection->title = $validated['why_title'];
        $whySection->description = $validated['why_description'];
        $whySection->feature_points = $validated['feature_points'] ?? null;
        $whySection->cta_label = $validated['cta_label'] ?? null;
        $whySection->cta_url = $validated['cta_url'] ?? null;
        $whySection->video_url = $validated['video_url'] ?? null;

        try {
            if ($request->hasFile('thumbnail')) {
                $whySection->thumbnail_path = $uploader->store(
                    $request->file('thumbnail'),
                    'uploads/why',
                    $whySection->thumbnail_path,
                );
            }
        } catch (RuntimeException $exception) {
            throw ValidationException::withMessages([
                'thumbnail' => 'Why section thumbnail upload failed. Please try another image.',
            ]);
        }

        $whySection->save();
        SiteCache::forgetPublicPages();

        return back()->with('success', 'Why section updated successfully.');
    }

    public function updateProjects(Request $request, PublicWebpUploader $uploader): RedirectResponse
    {
        $validated = $request->validate([
            'projects_top_title' => ['required', 'string', 'max:180'],
            'projects_bottom_title' => ['required', 'string', 'max:180'],
            'top_project_cards' => ['nullable', 'array'],
            'top_project_cards.*.order' => ['nullable', 'integer', 'min:1'],
            'top_project_cards.*.title' => ['nullable', 'string', 'max:120'],
            'top_project_cards.*.location' => ['nullable', 'string', 'max:120'],
            'top_project_cards.*.image_path' => ['nullable', 'string', 'max:2048'],
            'bottom_project_cards' => ['nullable', 'array'],
            'bottom_project_cards.*.order' => ['nullable', 'integer', 'min:1'],
            'bottom_project_cards.*.title' => ['nullable', 'string', 'max:120'],
            'bottom_project_cards.*.location' => ['nullable', 'string', 'max:120'],
            'bottom_project_cards.*.image_path' => ['nullable', 'string', 'max:2048'],
            'top_project_card_images' => ['nullable', 'array'],
            'bottom_project_card_images' => ['nullable', 'array'],
            'top_project_card_images.*' => ['nullable', 'image', 'max:6144'],
            'bottom_project_card_images.*' => ['nullable', 'image', 'max:6144'],
        ]);

        $projectSection = ProjectSection::query()->firstOrNew();
        $topCards = $this->storeProjectCards(
            $request,
            $uploader,
            $validated['top_project_cards'] ?? [],
            'top_project_card_images',
        );
        $bottomCards = $this->storeProjectCards(
            $request,
            $uploader,
            $validated['bottom_project_cards'] ?? [],
            'bottom_project_card_images',
        );

        $projectSection->top_title = $validated['projects_top_title'];
        $projectSection->top_button_label = null;
        $projectSection->top_button_url = null;
        $projectSection->bottom_title = $validated['projects_bottom_title'];
        $projectSection->top_cards = $topCards;
        $projectSection->bottom_cards = $bottomCards;
        $projectSection->save();
        SiteCache::forgetPublicPages();

        return back()->with('success', 'Our Projects section updated successfully.');
    }

    public function updateGallery(Request $request, PublicWebpUploader $uploader): RedirectResponse
    {
        $validated = $request->validate([
            'gallery_section_title' => ['nullable', 'string', 'max:180'],
            'gallery_section_subtitle' => ['nullable', 'string', 'max:180'],
            'gallery_view_all_label' => ['nullable', 'string', 'max:120'],
            'gallery_page_title' => ['nullable', 'string', 'max:180'],
            'gallery_page_subtitle' => ['nullable', 'string', 'max:255'],
            'featured_images' => ['nullable', 'array'],
            'featured_images.*.order' => ['nullable', 'integer', 'min:1', 'max:7'],
            'featured_images.*.image_path' => ['nullable', 'string', 'max:2048'],
            'featured_image_uploads' => ['nullable', 'array'],
            'featured_image_uploads.*' => ['nullable', 'image', 'max:6144'],
            'albums' => ['nullable', 'array'],
            'albums.*.title' => ['nullable', 'string', 'max:180'],
            'albums.*.subtitle' => ['nullable', 'string', 'max:255'],
            'albums.*.images' => ['nullable', 'array'],
            'albums.*.images.*.image_path' => ['nullable', 'string', 'max:2048'],
            'album_image_uploads' => ['nullable', 'array'],
            'album_image_uploads.*' => ['nullable', 'array'],
            'album_image_uploads.*.*' => ['nullable', 'image', 'max:6144'],
        ]);

        $featuredImages = collect(range(0, GallerySection::FEATURED_IMAGE_SLOTS - 1))
            ->map(function (int $index) use ($request, $uploader, $validated): ?array {
                $existingPath = $this->sanitizeManagedUploadPath(
                    data_get($validated, 'featured_images.'.$index.'.image_path'),
                    'uploads/gallery/featured',
                );
                $order = max(1, min(
                    GallerySection::FEATURED_IMAGE_SLOTS,
                    (int) data_get($validated, 'featured_images.'.$index.'.order', $index + 1)
                ));
                $imagePath = $existingPath !== '' ? $existingPath : null;

                try {
                    if ($request->hasFile('featured_image_uploads.'.$index)) {
                        $imagePath = $uploader->store(
                            $request->file('featured_image_uploads.'.$index),
                            'uploads/gallery/featured',
                            $imagePath,
                        );
                    }
                } catch (RuntimeException $exception) {
                    throw ValidationException::withMessages([
                        'featured_image_uploads.'.$index => 'Featured image upload failed. Please try another image.',
                    ]);
                }

                if (! filled($imagePath)) {
                    return null;
                }

                return [
                    'order' => $order,
                    'image_path' => $imagePath,
                ];
            })
            ->filter()
            ->values()
            ->all();

        $albums = collect($validated['albums'] ?? [])
            ->map(function ($album, $albumIndex) use ($request, $uploader): ?array {
                $data = is_array($album) ? $album : [];

                $images = collect($data['images'] ?? [])
                    ->map(function ($image, $imageIndex) use ($request, $uploader, $albumIndex): ?array {
                        $imageData = is_array($image) ? $image : [];
                        $imagePath = $this->sanitizeManagedUploadPath(
                            $imageData['image_path'] ?? null,
                            'uploads/gallery/albums',
                        );

                        try {
                            if ($request->hasFile('album_image_uploads.'.$albumIndex.'.'.$imageIndex)) {
                                $imagePath = $uploader->store(
                                    $request->file('album_image_uploads.'.$albumIndex.'.'.$imageIndex),
                                    'uploads/gallery/albums',
                                    $imagePath,
                                );
                            }
                        } catch (RuntimeException $exception) {
                            throw ValidationException::withMessages([
                                'album_image_uploads.'.$albumIndex.'.'.$imageIndex => 'Album image upload failed. Please try another image.',
                            ]);
                        }

                        if (! filled($imagePath)) {
                            return null;
                        }

                        return [
                            'image_path' => $imagePath,
                        ];
                    })
                    ->filter()
                    ->values()
                    ->all();

                $normalizedAlbum = [
                    'title' => trim((string) ($data['title'] ?? '')),
                    'subtitle' => trim((string) ($data['subtitle'] ?? '')),
                    'images' => $images,
                ];

                if (! filled($normalizedAlbum['title']) && ! filled($normalizedAlbum['subtitle']) && empty($normalizedAlbum['images'])) {
                    return null;
                }

                return $normalizedAlbum;
            })
            ->filter()
            ->values()
            ->all();

        $gallerySection = GallerySection::query()->firstOrNew();
        $gallerySection->section_title = filled($validated['gallery_section_title'] ?? null)
            ? trim((string) $validated['gallery_section_title'])
            : null;
        $gallerySection->section_subtitle = filled($validated['gallery_section_subtitle'] ?? null)
            ? trim((string) $validated['gallery_section_subtitle'])
            : null;
        $gallerySection->view_all_label = filled($validated['gallery_view_all_label'] ?? null)
            ? trim((string) $validated['gallery_view_all_label'])
            : null;
        $gallerySection->page_title = filled($validated['gallery_page_title'] ?? null)
            ? trim((string) $validated['gallery_page_title'])
            : null;
        $gallerySection->page_subtitle = filled($validated['gallery_page_subtitle'] ?? null)
            ? trim((string) $validated['gallery_page_subtitle'])
            : null;
        $gallerySection->featured_images = $featuredImages;
        $gallerySection->albums = $albums;
        $gallerySection->save();
        SiteCache::forgetPublicPages();

        return back()->with('success', 'Gallery section and albums updated successfully.');
    }

    public function updateReviews(Request $request, PublicWebpUploader $uploader): RedirectResponse
    {
        $validated = $request->validate([
            'review_section_title' => ['nullable', 'string', 'max:180'],
            'review_section_subtitle' => ['nullable', 'string', 'max:255'],
            'shareholder_reviews' => ['nullable', 'array'],
            'shareholder_reviews.*.name' => ['nullable', 'string', 'max:180'],
            'shareholder_reviews.*.video_url' => ['nullable', 'url', 'max:2048'],
            'shareholder_reviews.*.thumbnail_path' => ['nullable', 'string', 'max:2048'],
            'shareholder_reviews.*.remove_thumbnail' => ['nullable', 'boolean'],
            'shareholder_review_thumbnails' => ['nullable', 'array'],
            'shareholder_review_thumbnails.*' => ['nullable', 'image', 'max:6144'],
        ]);

        $reviews = collect($validated['shareholder_reviews'] ?? [])
            ->map(function ($review, $index) use ($request, $uploader): ?array {
                $data = is_array($review) ? $review : [];
                $name = trim((string) ($data['name'] ?? ''));
                $videoUrl = trim((string) ($data['video_url'] ?? ''));
                $thumbnailPath = $this->sanitizeManagedUploadPath($data['thumbnail_path'] ?? null, 'uploads/reviews');
                $hasUpload = $request->hasFile('shareholder_review_thumbnails.'.$index);
                $removeThumbnail = filter_var($data['remove_thumbnail'] ?? false, FILTER_VALIDATE_BOOLEAN);

                if (! filled($name) && ! filled($videoUrl) && ! filled($thumbnailPath) && ! $hasUpload) {
                    return null;
                }

                if (! filled($videoUrl) || ! ShareholderReviewSection::makeEmbedUrl($videoUrl)) {
                    throw ValidationException::withMessages([
                        'shareholder_reviews.'.$index.'.video_url' => 'Please provide a valid YouTube video link.',
                    ]);
                }

                try {
                    if ($hasUpload) {
                        $thumbnailPath = $uploader->store(
                            $request->file('shareholder_review_thumbnails.'.$index),
                            'uploads/reviews',
                            $thumbnailPath,
                        );
                    } elseif ($removeThumbnail && filled($thumbnailPath)) {
                        File::delete(public_path($thumbnailPath));
                        $thumbnailPath = '';
                    }
                } catch (RuntimeException $exception) {
                    throw ValidationException::withMessages([
                        'shareholder_review_thumbnails.'.$index => 'Review thumbnail upload failed. Please try another image.',
                    ]);
                }

                return [
                    'name' => $name,
                    'video_url' => $videoUrl,
                    'thumbnail_path' => $thumbnailPath,
                ];
            })
            ->filter()
            ->values()
            ->all();

        $reviewSection = ShareholderReviewSection::query()->firstOrNew();
        $reviewSection->section_title = filled($validated['review_section_title'] ?? null)
            ? trim((string) $validated['review_section_title'])
            : null;
        $reviewSection->section_subtitle = filled($validated['review_section_subtitle'] ?? null)
            ? trim((string) $validated['review_section_subtitle'])
            : null;
        $reviewSection->reviews = $reviews;
        $reviewSection->save();
        SiteCache::forgetPublicPages();

        return back()->with('success', 'Shareholder reviews updated successfully.');
    }

    public function updateLeadership(Request $request, PublicWebpUploader $uploader): RedirectResponse
    {
        $validated = $request->validate([
            'section_title' => ['nullable', 'string', 'max:180'],
            'founder_name' => ['nullable', 'string', 'max:180'],
            'founder_position' => ['nullable', 'string', 'max:180'],
            'founder_description' => ['nullable', 'string', 'max:200'],
            'founder_image_path' => ['nullable', 'string', 'max:2048'],
            'founder_image' => ['nullable', 'image', 'max:6144'],
            'board_members' => ['nullable', 'array'],
            'board_members.*.name' => ['nullable', 'string', 'max:180'],
            'board_members.*.position' => ['nullable', 'string', 'max:180'],
            'board_members.*.image_path' => ['nullable', 'string', 'max:2048'],
            'board_member_images' => ['nullable', 'array'],
            'board_member_images.*' => ['nullable', 'image', 'max:6144'],
        ]);

        $leadershipSection = LeadershipSection::query()->firstOrNew();
        $founderImagePath = $this->sanitizeManagedUploadPath(
            $validated['founder_image_path'] ?? $leadershipSection->founder_image_path,
            'uploads/leadership/founder',
        ) ?? $this->sanitizeManagedUploadPath($leadershipSection->founder_image_path, 'uploads/leadership/founder');

        try {
            if ($request->hasFile('founder_image')) {
                $founderImagePath = $uploader->store(
                    $request->file('founder_image'),
                    'uploads/leadership/founder',
                    $founderImagePath,
                );
            }
        } catch (RuntimeException $exception) {
            throw ValidationException::withMessages([
                'founder_image' => 'Founder image upload failed. Please try another image.',
            ]);
        }

        $boardMembers = collect($validated['board_members'] ?? [])
            ->map(function ($member, $index) use ($request, $uploader): ?array {
                $data = is_array($member) ? $member : [];
                $imagePath = $this->sanitizeManagedUploadPath($data['image_path'] ?? null, 'uploads/leadership/members');

                try {
                    if ($request->hasFile('board_member_images.'.$index)) {
                        $imagePath = $uploader->store(
                            $request->file('board_member_images.'.$index),
                            'uploads/leadership/members',
                            $imagePath,
                        );
                    }
                } catch (RuntimeException $exception) {
                    throw ValidationException::withMessages([
                        'board_member_images.'.$index => 'Board member image upload failed. Please try another image.',
                    ]);
                }

                $normalized = [
                    'name' => trim((string) ($data['name'] ?? '')),
                    'position' => trim((string) ($data['position'] ?? '')),
                    'image_path' => $imagePath,
                ];

                if (! filled($normalized['name']) && ! filled($normalized['position']) && ! filled($normalized['image_path'])) {
                    return null;
                }

                return $normalized;
            })
            ->filter()
            ->values()
            ->all();

        $leadershipSection->section_title = filled($validated['section_title'] ?? null)
            ? trim((string) $validated['section_title'])
            : null;
        $leadershipSection->founder_name = filled($validated['founder_name'] ?? null)
            ? trim((string) $validated['founder_name'])
            : null;
        $leadershipSection->founder_position = filled($validated['founder_position'] ?? null)
            ? trim((string) $validated['founder_position'])
            : null;
        $leadershipSection->founder_description = filled($validated['founder_description'] ?? null)
            ? trim((string) $validated['founder_description'])
            : null;
        $leadershipSection->founder_image_path = $founderImagePath;
        $leadershipSection->board_members = $boardMembers;
        $leadershipSection->is_visible = $request->boolean('is_visible');
        $leadershipSection->save();
        SiteCache::forgetPublicPages();

        return back()->with('success', 'Leadership section updated successfully.');
    }

    public function updateValuedShareholders(Request $request, PublicWebpUploader $uploader): RedirectResponse
    {
        $validated = $request->validate([
            'shareholder_section_title' => ['nullable', 'string', 'max:180'],
            'shareholder_section_visible' => ['nullable'],
            'shareholders' => ['nullable', 'array'],
            'shareholders.*.name' => ['nullable', 'string', 'max:180'],
            'shareholders.*.position' => ['nullable', 'string', 'max:180'],
            'shareholders.*.image_path' => ['nullable', 'string', 'max:2048'],
            'shareholder_images' => ['nullable', 'array'],
            'shareholder_images.*' => ['nullable', 'image', 'max:6144'],
        ]);

        $section = ValuedShareholderSection::query()->firstOrNew();

        $shareholders = collect($validated['shareholders'] ?? [])
            ->map(function ($shareholder, $index) use ($request, $uploader): ?array {
                $data = is_array($shareholder) ? $shareholder : [];
                $imagePath = $this->sanitizeManagedUploadPath($data['image_path'] ?? null, 'uploads/valued-shareholders');

                try {
                    if ($request->hasFile('shareholder_images.'.$index)) {
                        $imagePath = $uploader->store(
                            $request->file('shareholder_images.'.$index),
                            'uploads/valued-shareholders',
                            $imagePath,
                        );
                    }
                } catch (RuntimeException $exception) {
                    throw ValidationException::withMessages([
                        'shareholder_images.'.$index => 'Shareholder image upload failed. Please try another image.',
                    ]);
                }

                $normalized = [
                    'name' => trim((string) ($data['name'] ?? '')),
                    'position' => trim((string) ($data['position'] ?? '')),
                    'image_path' => $imagePath,
                ];

                if (! filled($normalized['name']) && ! filled($normalized['position']) && ! filled($normalized['image_path'])) {
                    return null;
                }

                return $normalized;
            })
            ->filter()
            ->values()
            ->all();

        $section->section_title = filled($validated['shareholder_section_title'] ?? null)
            ? trim((string) $validated['shareholder_section_title'])
            : null;
        $section->shareholders = $shareholders;
        $section->is_visible = $request->boolean('shareholder_section_visible');
        $section->save();
        SiteCache::forgetPublicPages();

        return back()->with('success', 'Valued shareholders section updated successfully.');
    }

    protected function storeProjectCards(
        Request $request,
        PublicWebpUploader $uploader,
        array $cards,
        string $imageInput,
    ): array {
        return collect($cards)
            ->map(function ($cardData, $index) use ($request, $uploader, $imageInput): ?array {
                $data = is_array($cardData) ? $cardData : [];
                $imagePath = $this->sanitizeManagedUploadPath($data['image_path'] ?? null, 'uploads/projects');

                try {
                    if ($request->hasFile($imageInput.'.'.$index)) {
                        $imagePath = $uploader->store(
                            $request->file($imageInput.'.'.$index),
                            'uploads/projects',
                            $imagePath,
                        );
                    }
                } catch (RuntimeException $exception) {
                    throw ValidationException::withMessages([
                        $imageInput.'.'.$index => 'Project card image upload failed. Please try another image.',
                    ]);
                }

                $card = [
                    'order' => max(1, (int) ($data['order'] ?? ($index + 1))),
                    'title' => trim((string) ($data['title'] ?? '')),
                    'location' => trim((string) ($data['location'] ?? '')),
                    'image_path' => $imagePath,
                    '_position' => $index,
                ];

                if (! filled($card['title']) && ! filled($card['location']) && ! filled($card['image_path'])) {
                    return null;
                }

                return $card;
            })
            ->filter()
            ->sortBy(fn (array $card): string => sprintf('%09d-%09d', $card['order'], $card['_position']))
            ->map(fn (array $card): array => [
                'order' => $card['order'],
                'title' => $card['title'],
                'location' => $card['location'],
                'image_path' => $card['image_path'],
            ])
            ->values()
            ->all();
    }

    protected function assertYoutubeUrl(?string $url, string $field): void
    {
        if (! filled($url)) {
            return;
        }

        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

        if (! str_contains($host, 'youtube.com') && $host !== 'youtu.be' && ! str_contains($host, 'youtube-nocookie.com')) {
            throw ValidationException::withMessages([
                $field => 'Please provide a valid YouTube video link.',
            ]);
        }
    }

    protected function sanitizeRichText(?string $html): ?string
    {
        return RichTextSanitizer::sanitize($html);
    }

    protected function sanitizeManagedUploadPath(mixed $path, string $directory): ?string
    {
        if (! filled($path)) {
            return null;
        }

        $normalized = ltrim(str_replace('\\', '/', trim((string) $path)), '/');
        $directory = trim(str_replace('\\', '/', $directory), '/');

        if (
            $normalized === ''
            || str_contains($normalized, '..')
            || preg_match('/^[A-Za-z]:/', $normalized) === 1
            || ! str_starts_with($normalized, $directory.'/')
        ) {
            return null;
        }

        return $normalized;
    }
}
