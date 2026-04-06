<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CmsPage;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(PaymentEmailTemplateSeeder::class);

        // CMS Pages
        CmsPage::firstOrCreate(
            ['slug' => 'cookies-policy'],
            [
                'title'   => 'Cookies Policy',
                'content' => '<h1>Cookies Policy</h1>
<p>Last updated: April 06, 2026</p>
<p>This Cookies Policy explains what Cookies are and how We use them. You should read this policy so You can understand what type of cookies We use, or the information We collect using Cookies and how that information is used. This Cookies Policy has been created with the help of the <a href="https://www.termsfeed.com/cookies-policy-generator/" target="_blank">Cookies Policy Generator</a>.</p>
<p>Cookies do not typically contain any information that personally identifies a user, but personal information that We store about You may be linked to the information stored in and obtained from Cookies. For further information on how We use, store and keep your personal data secure, see our Privacy Policy, if and when We make it available within the Website or on our website.</p>
<p>We do not store sensitive personal information, such as mailing addresses, account passwords, etc. in the Cookies We use.</p>
<h2>Interpretation and Definitions</h2>
<h3>Interpretation</h3>
<p>The words whose initial letters are capitalized have meanings defined under the following conditions. The following definitions shall have the same meaning regardless of whether they appear in singular or in plural.</p>
<h3>Definitions</h3>
<p>For the purposes of this Cookies Policy:</p>
<ul>
<li><strong>Company</strong> (referred to as either &quot;the Company&quot;, &quot;We&quot;, &quot;Us&quot; or &quot;Our&quot; in this Cookies Policy) refers to voxura, suffareem st.</li>
<li><strong>Cookies</strong> means small files that are placed on Your computer, mobile device or any other device by a website, containing details of your browsing history on that website among its many uses.</li>
<li><strong>Website</strong> refers to voxura, accessible from <a href="voxura.local" rel="external nofollow noopener" target="_blank">voxura.local</a>.</li>
<li><strong>You</strong> means the individual accessing or using the Website, or a company, or any legal entity on behalf of which such individual is accessing or using the Website, as applicable.</li>
</ul>
<h2>The use of the Cookies</h2>
<h3>Type of Cookies We Use</h3>
<p>Cookies can be &quot;Persistent&quot; or &quot;Session&quot; Cookies. Persistent Cookies remain on your personal computer or mobile device when You go offline, while Session Cookies are deleted as soon as You close your web browser.</p>
<p>Where required by law, We will request your consent before using Cookies that are not strictly necessary. Strictly necessary Cookies are used to provide the Website and cannot be switched off in our systems.</p>
<p>We use both session and persistent Cookies for the purposes set out below:</p>
<ul>
<li>
<p><strong>Necessary / Essential Cookies</strong></p>
<p>Type: Session Cookies</p>
<p>Administered by: Us</p>
<p>Purpose: These Cookies are essential to provide You with services available through the Website and to enable You to use some of its features. They help to authenticate users and prevent fraudulent use of user accounts. Without these Cookies, the services that You have asked for cannot be provided, and We only use these Cookies to provide You with those services.</p>
</li>
<li>
<p><strong>Functionality Cookies</strong></p>
<p>Type: Persistent Cookies</p>
<p>Administered by: Us</p>
<p>Purpose: These Cookies allow Us to remember choices You make when You use the Website, such as remembering your login details or language preference. The purpose of these Cookies is to provide You with a more personal experience and to avoid You having to re-enter your preferences every time You use the Website.</p>
</li>
</ul>
<h3>Your Choices Regarding Cookies</h3>
<p>If You prefer to avoid the use of Cookies on the Website, first You must disable the use of Cookies in your browser and then delete the Cookies saved in your browser associated with the Website. You may use this option for preventing the use of Cookies at any time.</p>
<p>If You do not accept Our Cookies, You may experience some inconvenience in your use of the Website and some features may not function properly.</p>
<p>If You like to delete Cookies or instruct your web browser to delete or refuse Cookies, please visit the help pages of your web browser.</p>
<ul>
<li><p>For the Chrome web browser, please visit this page from Google: <a href="https://support.google.com/accounts/answer/32050" rel="external nofollow noopener" target="_blank">https://support.google.com/accounts/answer/32050</a></p></li>
<li><p>For the Microsoft Edge browser, please visit this page from Microsoft: <a href="https://support.microsoft.com/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" rel="external nofollow noopener" target="_blank">https://support.microsoft.com/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09</a></p></li>
<li><p>For the Firefox web browser, please visit this page from Mozilla: <a href="https://support.mozilla.org/en-US/kb/delete-cookies-remove-info-websites-stored" rel="external nofollow noopener" target="_blank">https://support.mozilla.org/en-US/kb/delete-cookies-remove-info-websites-stored</a></p></li>
<li><p>For the Safari web browser, please visit this page from Apple: <a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac" rel="external nofollow noopener" target="_blank">https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac</a></p></li>
</ul>
<p>For any other web browser, please visit your web browser official web pages.</p>
<h3>Changes to this Cookies Policy</h3>
<p>We may update this Cookies Policy from time to time. The &quot;Last updated&quot; date at the top indicates when it was last revised.</p>
<h3>Contact Us</h3>
<p>If you have any questions about this Cookies Policy, You can contact us:</p>
<ul>
<li>By visiting this page on our website: <a href="http://voxura.local/#contact" rel="external nofollow noopener" target="_blank">http://voxura.local/#contact</a></li>
</ul>',
                'status' => 'active',
            ]
        );


        // User
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
            ],
        );

        User::updateOrCreate(
            ['email' => 'islamdraidi@gmail.com'],
            [
                'name' => 'Islam Draidi',
                'password' => '123123123',
                'role' => 'admin',
                'is_blocked' => false,
            ],
        );

        // Categories
        $outerwear = Category::firstOrCreate([
            'name' => 'Outerwear',
            'slug' => 'outerwear',
        ]);

        $watches = Category::firstOrCreate([
            'name' => 'Watches',
            'slug' => 'watches',
        ]);

        $bags = Category::firstOrCreate([
            'name' => 'Bags',
            'slug' => 'bags',
        ]);

        $dresses = Category::firstOrCreate([
            'name' => 'Dresses',
            'slug' => 'dresses',
        ]);

        $footwear = Category::firstOrCreate([
            'name' => 'Footwear',
            'slug' => 'footwear',
        ]);

        $jewelry = Category::firstOrCreate([
            'name' => 'Jewelry',
            'slug' => 'jewelry',
        ]);

        $accessories = Category::firstOrCreate([
            'name' => 'Accessories',
            'slug' => 'accessories',
        ]);

        // Products
        Product::updateOrCreate([
            'slug' => 'voxura-leather-jacket',
        ], [
            'name' => 'Voxura Leather Jacket',
            'description' => 'Premium genuine leather jacket with modern fit',
            'price' => 599,
            'stock' => 50,
            'image' => 'jacket.jpg',
            'category_id' => $outerwear->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-luxury-watch',
        ], [
            'name' => 'Voxura Luxury Watch',
            'description' => 'Swiss-made luxury timepiece with sapphire crystal',
            'price' => 899,
            'stock' => 30,
            'image' => 'watch.jpg',
            'category_id' => $watches->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-designer-handbag',
        ], [
            'name' => 'Voxura Designer Handbag',
            'description' => 'Handcrafted leather handbag with signature design',
            'price' => 1299,
            'stock' => 20,
            'image' => 'handbag.jpg',
            'category_id' => $bags->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-premium-sneakers',
        ], [
            'name' => 'Voxura Premium Sneakers',
            'description' => 'Limited edition sneakers with comfort cushioning',
            'price' => 249,
            'stock' => 25,
            'image' => 'sneakers.jpg',
            'category_id' => $footwear->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-gold-necklace',
        ], [
            'name' => 'Voxura Gold Necklace',
            'description' => '18k gold plated chain necklace',
            'price' => 549,
            'stock' => 15,
            'image' => 'necklace.jpg',
            'category_id' => $jewelry->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-wool-coat',
        ], [
            'name' => 'Voxura Wool Coat',
            'description' => 'Double breasted wool coat with tailored fit',
            'price' => 799,
            'stock' => 35,
            'image' => 'coat.jpg',
            'category_id' => $outerwear->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-designer-sunglasses',
        ], [
            'name' => 'Voxura Designer Sunglasses',
            'description' => 'Polarized sunglasses with UV protection',
            'price' => 329,
            'stock' => 39,
            'image' => 'sunglasses.jpg',
            'category_id' => $accessories->id,
        ]);

        Product::updateOrCreate([
            'slug' => 'voxura-silk-dress',
        ], [
            'name' => 'Voxura Silk Dress',
            'description' => 'Elegant silk dress with flowing silhouette',
            'price' => 499,
            'stock' => 25,
            'image' => 'dress.jpg',
            'category_id' => $dresses->id,
        ]);
    }
}
