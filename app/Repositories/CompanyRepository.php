<?php


namespace App\Repositories;


use App\Helpers\Nested;
use App\Models\Company\Company;
use App\Models\Company\CompanyAddress;
use App\Models\Company\CompanyEmail;
use App\Models\Company\CompanyPhone;
use App\Models\Company\CompanyTimework;
use App\Models\Company\CompanyWebsite;
use App\Models\Finder\File;
use App\Models\Finder\Folder;
use App\Models\Meta;
use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class CompanyRepository
{
    /**
     * @param string $uuid
     * @param string $locale
     * @param array $data
     * @param array $meta
     * @return Company
     */
    public function store(string $uuid, string $locale, array $data, array $meta): Company
    {
        /*  */
        $company = Company::find($uuid);
        if (!$company) {
            $company = new Company();
            $company->uuid = $uuid;
        }

        /*  */
        $phones = $data['phone'] ?? [];
        $emails = $data['email'] ?? [];
        $websites = $data['website'] ?? [];
        $address = $data['address'] ? [
            $company->address->uuid ?? Uuid::uuid4()->toString() => [
                'value' => $data['address'] ?? ''
            ]
        ] : [];
        $timework = $data['timework'] ? [
            $company->timework->uuid ?? Uuid::uuid4()->toString() => [
                'value' => $data['timework'] ?? ''
            ]
        ] : [];

        unset(
            $data['phone'],
            $data['email'],
            $data['website'],
            $data['address'],
            $data['timework']
        );

        $company->setDefaultLocale($locale);
        $company->fill($data);

        if (empty($meta['is_active'])) $meta['is_active'] = false;
        if (empty($meta['show_nested'])) $meta['show_nested'] = false;
        $company->meta->setDefaultLocale($locale)->fill($meta);

        $company->push();
        /* ------------------------------------------------------------------ */

        $this->updateContacts($phones, $company, CompanyPhone::class, $locale, 'number');
        $this->updateContacts($emails, $company, CompanyEmail::class, $locale);
        $this->updateContacts($websites, $company, CompanyWebsite::class);
        $this->updateContacts($timework, $company, CompanyTimework::class, $locale);
        $this->updateContacts($address, $company, CompanyAddress::class, $locale);

        /* ------------------------------------------------------------------ */

        $company->updateWidget();

        if (key_exists('image', $data)) {
            $this->uploadImage($company, $data['image']);
        }

        return $company;
    }

    /**
     * @param array $data
     * @param Company $company
     * @param string $model
     * @param string|null $locale
     * @param string $primaryKey
     */
    public function updateContacts(
        array $data,
        Company $company,
        string $model,
        string $locale = null,
        string $primaryKey = 'value'
    ): void {
        foreach($data as $id => $item_data) {
            /* @var Model $item */
            $item = $model::find($id);

            if (!$item) {
                $item = new $model;
                $item->uuid = $id;
                $item->company_uuid = $company->uuid;
            }

            if ($locale) {
                $item->setDefaultLocale($locale);
            }

            if (empty($item_data[$primaryKey])) {
                $item->delete();
            } else {
                $item->fill($item_data)->save();
//                if ($model == CompanyTimework::class) {
//                    dd($item);
//                }
            }
        }
    }

    /**
     * @param string $uuid
     * @return bool
     */
    public function delete(string $uuid): bool
    {
        return Company::find($uuid)->delete();
    }

    /**
     * @param Company $company
     * @param string $uuid
     * @param string|null $number
     * @param string|null $description
     * @return CompanyPhone
     */
    public function addPhone(
        Company $company,
        string $uuid,
        string $number = null,
        string $description = null
    ): CompanyPhone
    {
        $phone = CompanyPhone::find($uuid);
        if (!$phone) {
            $phone = new CompanyPhone();
            $phone->uuid = $uuid;
            $phone->company_uuid = $company->uuid;
        }

        $phone->number = trim($number);
        $phone->description = trim($description);

        if (empty(trim($number))) {
            $phone->delete();
        } else {
            $phone->save();
        }

        return $phone;
    }

    /**
     * @param Company $company
     * @param string $uuid
     * @param string|null $value
     * @param string|null $description
     * @return CompanyEmail
     */
    public function addEmail(
        Company $company,
        string $uuid,
        string $value = null,
        string $description = null
    ): CompanyEmail
    {
        $email = CompanyEmail::find($uuid);
        if (!$email) {
            $email = new CompanyEmail();
            $email->uuid = $uuid;
            $email->company_uuid = $company->uuid;
        }

        $email->value = trim($value);
        $email->description = trim($description);

        if (empty(trim($value))) {
            $email->delete();
        } else {
            $email->save();
        }

        return $email;
    }

    /**
     * @param Company $company
     * @param string $uuid
     * @param string $value
     * @return CompanyWebsite
     */
    public function addWebsite(Company $company, string $uuid, string $value): CompanyWebsite
    {
        $website = CompanyWebsite::find($uuid);
        if (!$website) {
            $website = new CompanyWebsite();
            $website->uuid = $uuid;
            $website->company_uuid = $company->uuid;
        }

        $website->value = trim($value);

        if (empty(trim($value))) {
            $website->delete();
        } else {
            $website->save();
        }

        return $website;
    }

    /**
     * @param Company $company
     * @param string|null $value
     * @return CompanyAddress
     */
    public function addAddress(Company $company, string $value = null): CompanyAddress
    {
        $address = CompanyAddress::where(['company_uuid' => $company->uuid])->first();
        if (!$address) {
            $address = new CompanyAddress();
            $address->uuid = Uuid::uuid4()->toString();
            $address->company_uuid = $company->uuid;
        }

        $address->value = trim($value);

        if (empty(trim($value))) {
            $address->delete();
        } else {
            $address->save();
        }

        return $address;
    }

    /**
     * @param Company $company
     * @param string|null $value
     * @return CompanyTimework
     */
    public function addTimework(Company $company, string $value = null): CompanyTimework
    {
        $timework = CompanyTimework::where([
            'company_uuid' => $company->uuid
        ])->first();

        if (!$timework) {
            $timework = new CompanyTimework();
            $timework->uuid = Uuid::uuid4()->toString();
            $timework->company_uuid = $company->uuid;
        }

        $timework->value = trim($value);

        if (empty(trim($value))) {
            $timework->delete();
        } else {
            $timework->save();
        }

        return $timework;
    }

    /**
     * Загрузить изображение офиса
     * @param Company $company
     * @param UploadedFile $file
     */
    public function uploadImage(Company $company, UploadedFile $file)
    {
        if (!$file) return;

        /* Удалим предыдущий файл если он был */
        if ($company->image) $company->image->delete();

        /* Создание каталогов */
        $module_folder = Folder::createFolder(class_basename($company));
        $item_folder = Folder::createFolder($company->name, $module_folder->uuid);

        /* Загружаем файл в каталог товара */
        File::upload($file, $item_folder, $company->uuid);
    }

    /**
     * Типы офисов
     * @return string[]
     */
    public static function types(): array
    {
        return [
            'office' => 'Офис',
            'shop' => 'Магазин',
            'warehouse' => 'Склад',
        ];
    }

}
