<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSettingsRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Requests\UpdateIdentityRequest;
use App\Http\Requests\UpdateAboutRequest;
use App\Http\Requests\UploadGalleryRequest;
use App\Models\WebsiteSetting;
use App\Models\AboutPage;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Tampilkan halaman settings
     */
    public function index()
    {
        $settings = WebsiteSetting::getInstance();
        $about = AboutPage::getInstance();
        
        return view('admin.settings.index', [
            'settings' => $settings,
            'about' => $about,
        ]);
    }

    /**
     * Update pengaturan umum
     */
    public function updateGeneral(UpdateSettingsRequest $request)
    {
        try {
            $settings = WebsiteSetting::getInstance();
            
            $settings->update([
                'timezone' => $request->timezone,
                'date_format' => $request->date_format,
                'products_per_page' => $request->products_per_page,
                'maintenance_mode' => $request->boolean('maintenance_mode', false),
            ]);

            return back()->with('success', 'Pengaturan umum berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update informasi kontak
     */
    public function updateContact(UpdateContactRequest $request)
    {
        try {
            $settings = WebsiteSetting::getInstance();
            
            $settings->update([
                'email' => $request->email,
                'phone' => $request->phone,
                'whatsapp' => $request->whatsapp,
                'address' => $request->address,
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return back()->with('success', 'Informasi kontak berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update identitas website
     */
    public function updateIdentity(UpdateIdentityRequest $request)
    {
        try {
            $settings = WebsiteSetting::getInstance();
            
            $data = [
                'app_name' => $request->app_name,
                'slogan' => $request->slogan,
            ];

            // Handle Logo Upload
            if ($request->hasFile('logo')) {
                $logo = $this->settingService->uploadFile(
                    $request->file('logo'),
                    'logos',
                    $settings->logo
                );
                $data['logo'] = $logo;
            }

            // Handle Favicon Upload
            if ($request->hasFile('favicon')) {
                $favicon = $this->settingService->uploadFile(
                    $request->file('favicon'),
                    'favicons',
                    $settings->favicon
                );
                $data['favicon'] = $favicon;
            }

            $settings->update($data);

            return back()->with('success', 'Identitas website berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update halaman tentang kami
     */
    public function updateAbout(UpdateAboutRequest $request)
    {
        try {
            $about = AboutPage::getInstance();
            
            $about->update([
                'title' => $request->title,
                'about_content' => $request->about_content,
                'why_choose_us' => $request->why_choose_us,
                'how_to_shop' => $request->how_to_shop,
                'facilities' => $request->facilities,
                'contact_address' => $request->contact_address,
                'contact_whatsapp' => $request->contact_whatsapp,
                'contact_instagram' => $request->contact_instagram,
                'contact_phone' => $request->contact_phone,
                'operation_hours' => $request->operation_hours,
            ]);

            // Catatan: Jika model AboutPage Anda nantinya menerapkan cache terpisah,
            // Anda bisa membersihkan cache-nya di sini (misal: AboutPage::clearCache()).

            return back()->with('success', 'Halaman tentang kami berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Upload galeri foto
     */
    public function uploadGallery(UploadGalleryRequest $request, $index)
    {
        try {
            // Validasi index
            if (!in_array($index, range(1, 5))) {
                return back()->with('error', 'Index galeri tidak valid.');
            }

            $about = AboutPage::getInstance();
            $galleryKey = "gallery_{$index}";
            
            // Hapus file lama
            $this->settingService->deleteFile($about->{$galleryKey});

            // Upload file baru
            $path = $this->settingService->uploadFile(
                $request->file('gallery'),
                'galleries'
            );

            $about->update([$galleryKey => $path]);

            return back()->with('success', "Galeri {$index} berhasil diupload.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus galeri foto
     */
    public function deleteGallery($index)
    {
        try {
            // Validasi index
            if (!in_array($index, range(1, 5))) {
                return back()->with('error', 'Index galeri tidak valid.');
            }

            $about = AboutPage::getInstance();
            $galleryKey = "gallery_{$index}";
            
            // Hapus file
            $this->settingService->deleteFile($about->{$galleryKey});

            // Update database
            $about->update([$galleryKey => null]);

            return back()->with('success', "Galeri {$index} berhasil dihapus.");
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * API: Get settings data (untuk AJAX)
     */
    public function getData()
    {
        $settings = WebsiteSetting::getInstance();
        $about = AboutPage::getInstance();

        return response()->json([
            'settings' => $settings,
            'about' => $about,
        ]);
    }
}