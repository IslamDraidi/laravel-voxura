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

        CmsPage::updateOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'title'   => 'Privacy Policy',
                'content' => '<style>
[data-custom-class="body"],[data-custom-class="body"] *{background:transparent!important}
[data-custom-class="title"],[data-custom-class="title"] *{font-family:Arial!important;font-size:26px!important;color:#000!important}
[data-custom-class="subtitle"],[data-custom-class="subtitle"] *{font-family:Arial!important;color:#595959!important;font-size:14px!important}
[data-custom-class="heading_1"],[data-custom-class="heading_1"] *{font-family:Arial!important;font-size:19px!important;color:#000!important}
[data-custom-class="heading_2"],[data-custom-class="heading_2"] *{font-family:Arial!important;font-size:17px!important;color:#000!important}
[data-custom-class="body_text"],[data-custom-class="body_text"] *{color:#595959!important;font-size:14px!important;font-family:Arial!important}
[data-custom-class="link"],[data-custom-class="link"] *{color:#3030F1!important;font-size:14px!important;font-family:Arial!important;word-break:break-word!important}
ul{list-style-type:square}ul>li>ul{list-style-type:circle}ul>li>ul>li>ul{list-style-type:square}ol li{font-family:Arial}
</style>
<div data-custom-class="body">
<h1>PRIVACY POLICY</h1>
<p><strong>Last updated April 05, 2026</strong></p>
<p>This Privacy Notice for <strong>Voxura</strong> (doing business as <strong>voxura</strong>) describes how and why we might access, collect, store, use, and/or share your personal information when you use our services, including when you:</p>
<ul>
<li>Visit our website at <a href="http://voxura.local" target="_blank">voxura.local</a></li>
<li>Use Voxura. Voxura is an online fashion and apparel store that allows customers to browse, search, and purchase clothing, accessories, watches, bags, and footwear. Customers can create accounts, manage orders, save items to a wishlist, apply coupons, and pay securely through Tap Payments. Voxura also features 3D product modeling, allowing customers to view products interactively in three dimensions before making a purchase.</li>
<li>Engage with us in other related ways, including any marketing or events</li>
</ul>
<p><strong>Questions or concerns?</strong> Reading this Privacy Notice will help you understand your privacy rights and choices. If you do not agree with our policies and practices, please do not use our Services. If you still have any questions or concerns, please contact us at <a href="mailto:islamdraidi85@gmail.com">islamdraidi85@gmail.com</a>.</p>

<h2>SUMMARY OF KEY POINTS</h2>
<p><strong>What personal information do we process?</strong> When you visit, use, or navigate our Services, we may process personal information depending on how you interact with us and the Services, the choices you make, and the products and features you use.</p>
<p><strong>Do we process any sensitive personal information?</strong> We do not process sensitive personal information.</p>
<p><strong>Do we collect any information from third parties?</strong> We do not collect any information from third parties.</p>
<p><strong>How do we process your information?</strong> We process your information to provide, improve, and administer our Services, communicate with you, for security and fraud prevention, and to comply with law.</p>
<p><strong>In what situations and with which parties do we share personal information?</strong> We may share information in specific situations and with specific third parties.</p>
<p><strong>How do we keep your information safe?</strong> We have adequate organizational and technical processes and procedures in place to protect your personal information.</p>
<p><strong>What are your rights?</strong> Depending on where you are located geographically, the applicable privacy law may mean you have certain rights regarding your personal information.</p>
<p><strong>How do you exercise your rights?</strong> The easiest way to exercise your rights is by visiting <a href="http://voxura.local/profile" target="_blank">http://voxura.local/profile</a>, or by contacting us.</p>

<h2 id="toc">TABLE OF CONTENTS</h2>
<ol>
<li><a href="#infocollect">WHAT INFORMATION DO WE COLLECT?</a></li>
<li><a href="#infouse">HOW DO WE PROCESS YOUR INFORMATION?</a></li>
<li><a href="#legalbases">WHAT LEGAL BASES DO WE RELY ON TO PROCESS YOUR PERSONAL INFORMATION?</a></li>
<li><a href="#whoshare">WHEN AND WITH WHOM DO WE SHARE YOUR PERSONAL INFORMATION?</a></li>
<li><a href="#cookies">DO WE USE COOKIES AND OTHER TRACKING TECHNOLOGIES?</a></li>
<li><a href="#ai">DO WE OFFER ARTIFICIAL INTELLIGENCE-BASED PRODUCTS?</a></li>
<li><a href="#inforetain">HOW LONG DO WE KEEP YOUR INFORMATION?</a></li>
<li><a href="#infosafe">HOW DO WE KEEP YOUR INFORMATION SAFE?</a></li>
<li><a href="#infominors">DO WE COLLECT INFORMATION FROM MINORS?</a></li>
<li><a href="#privacyrights">WHAT ARE YOUR PRIVACY RIGHTS?</a></li>
<li><a href="#DNT">CONTROLS FOR DO-NOT-TRACK FEATURES</a></li>
<li><a href="#uslaws">DO UNITED STATES RESIDENTS HAVE SPECIFIC PRIVACY RIGHTS?</a></li>
<li><a href="#otherlaws">DO OTHER REGIONS HAVE SPECIFIC PRIVACY RIGHTS?</a></li>
<li><a href="#policyupdates">DO WE MAKE UPDATES TO THIS NOTICE?</a></li>
<li><a href="#contact">HOW CAN YOU CONTACT US ABOUT THIS NOTICE?</a></li>
<li><a href="#request">HOW CAN YOU REVIEW, UPDATE, OR DELETE THE DATA WE COLLECT FROM YOU?</a></li>
</ol>

<h2 id="infocollect">1. WHAT INFORMATION DO WE COLLECT?</h2>
<h3>Personal information you disclose to us</h3>
<p>We collect personal information that you voluntarily provide to us when you register on the Services, express an interest in obtaining information about us or our products and Services, when you participate in activities on the Services, or otherwise when you contact us.</p>
<p><strong>Personal Information Provided by You.</strong> The personal information we collect may include: names, email addresses, usernames, passwords, billing addresses, debit/credit card numbers.</p>
<p><strong>Sensitive Information.</strong> We do not process sensitive information.</p>
<p>All personal information that you provide to us must be true, complete, and accurate, and you must notify us of any changes to such personal information.</p>

<h2 id="infouse">2. HOW DO WE PROCESS YOUR INFORMATION?</h2>
<p><strong><em>In Short: We process your information to provide, improve, and administer our Services, communicate with you, for security and fraud prevention, and to comply with law.</em></strong></p>
<p>We process your personal information for a variety of reasons, depending on how you interact with our Services, including:</p>
<ul>
<li><strong>To facilitate account creation and authentication and otherwise manage user accounts.</strong></li>
<li><strong>To deliver and facilitate delivery of services to the user.</strong></li>
<li><strong>To respond to user inquiries/offer support to users.</strong></li>
<li><strong>To send administrative information to you.</strong></li>
<li><strong>To save or protect an individual\'s vital interest.</strong></li>
</ul>

<h2 id="legalbases">3. WHAT LEGAL BASES DO WE RELY ON TO PROCESS YOUR INFORMATION?</h2>
<p><em>We only process your personal information when we believe it is necessary and we have a valid legal reason to do so under applicable law.</em></p>
<p><strong>If you are located in the EU or UK, this section applies to you.</strong></p>
<p>We may rely on the following legal bases: Consent, Performance of a Contract, Legal Obligations, and Vital Interests.</p>
<p><strong>If you are located in Canada, this section applies to you.</strong></p>
<p>We may process your information if you have given us specific permission (express consent) or in situations where your permission can be inferred (implied consent). You can withdraw your consent at any time.</p>

<h2 id="whoshare">4. WHEN AND WITH WHOM DO WE SHARE YOUR PERSONAL INFORMATION?</h2>
<p><em>We may share information in specific situations and with specific third parties.</em></p>
<p>We may need to share your personal information in the following situations:</p>
<ul>
<li><strong>Business Transfers.</strong> We may share or transfer your information in connection with any merger, sale of company assets, financing, or acquisition.</li>
<li><strong>Business Partners.</strong> We may share your information with our business partners to offer you certain products, services, or promotions.</li>
</ul>

<h2 id="cookies">5. DO WE USE COOKIES AND OTHER TRACKING TECHNOLOGIES?</h2>
<p><em>We may use cookies and other tracking technologies to collect and store your information.</em></p>
<p>We may use cookies and similar tracking technologies to gather information when you interact with our Services. Some online tracking technologies help us maintain the security of our Services and your account, prevent crashes, fix bugs, save your preferences, and assist with basic site functions.</p>
<p>Specific information about how we use such technologies and how you can refuse certain cookies is set out in our Cookie Notice: <a href="http://voxura.local/pages/cookies-policy" target="_blank">http://voxura.local/pages/cookies-policy</a></p>

<h2 id="ai">6. DO WE OFFER ARTIFICIAL INTELLIGENCE-BASED PRODUCTS?</h2>
<p><em>We offer products, features, or tools powered by artificial intelligence, machine learning, or similar technologies.</em></p>
<p>As part of our Services, we offer AI Products including: AI predictive analytics and Machine learning models. All personal information processed using our AI Products is handled in line with this Privacy Notice.</p>

<h2 id="inforetain">7. HOW LONG DO WE KEEP YOUR INFORMATION?</h2>
<p><em>We keep your information for as long as necessary to fulfill the purposes outlined in this Privacy Notice unless otherwise required by law.</em></p>
<p>We will only keep your personal information for as long as it is necessary for the purposes set out in this Privacy Notice. When we have no ongoing legitimate business need to process your personal information, we will either delete or anonymize such information.</p>

<h2 id="infosafe">8. HOW DO WE KEEP YOUR INFORMATION SAFE?</h2>
<p><em>We aim to protect your personal information through a system of organizational and technical security measures.</em></p>
<p>We have implemented appropriate and reasonable technical and organizational security measures designed to protect the security of any personal information we process. However, no electronic transmission over the Internet or information storage technology can be guaranteed to be 100% secure.</p>

<h2 id="infominors">9. DO WE COLLECT INFORMATION FROM MINORS?</h2>
<p><em>We do not knowingly collect data from or market to minors.</em></p>

<h2 id="privacyrights">10. WHAT ARE YOUR PRIVACY RIGHTS?</h2>
<p><em>Depending on your state of residence in the US or in some regions, such as the European Economic Area (EEA), United Kingdom (UK), Switzerland, and Canada, you have rights that allow you greater access to and control over your personal information.</em></p>
<p>In some regions, you have the right to: request access and obtain a copy of your personal information, request rectification or erasure, restrict the processing of your personal information, and data portability.</p>
<h3>Account Information</h3>
<p>If you would like to review or change the information in your account or terminate your account, you can log in to your account settings at <a href="http://voxura.local/profile" target="_blank">http://voxura.local/profile</a>.</p>
<p><strong>Cookies and similar technologies:</strong> For further information, please see our Cookie Notice: <a href="http://voxura.local/pages/cookies-policy" target="_blank">http://voxura.local/pages/cookies-policy</a></p>
<p>If you have questions or comments about your privacy rights, you may email us at <a href="mailto:islamdraidi85@gmail.com">islamdraidi85@gmail.com</a>.</p>

<h2 id="DNT">11. CONTROLS FOR DO-NOT-TRACK FEATURES</h2>
<p>Most web browsers include a Do-Not-Track ("DNT") feature. We do not currently respond to DNT browser signals or any other mechanism that automatically communicates your choice not to be tracked online.</p>

<h2 id="uslaws">12. DO UNITED STATES RESIDENTS HAVE SPECIFIC PRIVACY RIGHTS?</h2>
<p><em>If you are a resident of California, Colorado, Connecticut, Delaware, Florida, or other applicable US states, you may have the right to request access to and receive details about the personal information we maintain about you.</em></p>
<h3>Your Rights</h3>
<ul>
<li><strong>Right to know</strong> whether or not we are processing your personal data</li>
<li><strong>Right to access</strong> your personal data</li>
<li><strong>Right to correct</strong> inaccuracies in your personal data</li>
<li><strong>Right to request</strong> the deletion of your personal data</li>
<li><strong>Right to obtain a copy</strong> of the personal data you previously shared with us</li>
<li><strong>Right to non-discrimination</strong> for exercising your rights</li>
<li><strong>Right to opt out</strong> of the processing of your personal data if it is used for targeted advertising</li>
</ul>
<h3>How to Exercise Your Rights</h3>
<p>To exercise these rights, you can contact us by visiting <a href="http://voxura.local/profile" target="_blank">http://voxura.local/profile</a> or <a href="http://voxura.local/#contact" target="_blank">http://voxura.local/#contact</a>.</p>

<h2 id="otherlaws">13. DO OTHER REGIONS HAVE SPECIFIC PRIVACY RIGHTS?</h2>
<p><em>You may have additional rights based on the country you reside in.</em></p>
<h3>Australia and New Zealand</h3>
<p>We collect and process your personal information under the obligations and conditions set by Australia\'s Privacy Act 1988 and New Zealand\'s Privacy Act 2020.</p>
<h3>Republic of South Africa</h3>
<p>At any time, you have the right to request access to or correction of your personal information by contacting us using the details provided below.</p>

<h2 id="policyupdates">14. DO WE MAKE UPDATES TO THIS NOTICE?</h2>
<p><em>Yes, we will update this notice as necessary to stay compliant with relevant laws.</em></p>
<p>We may update this Privacy Notice from time to time. The updated version will be indicated by an updated "Revised" date at the top of this Privacy Notice.</p>

<h2 id="contact">15. HOW CAN YOU CONTACT US ABOUT THIS NOTICE?</h2>
<p>If you have questions or comments about this notice, you may email us at <a href="mailto:islamdraidi85@gmail.com">islamdraidi85@gmail.com</a> or contact us by post at:</p>
<p>Voxura<br>suffareem st<br>talkurm, westbank/talkum/Beitleed 330<br>Palestinian Territories</p>

<h2 id="request">16. HOW CAN YOU REVIEW, UPDATE, OR DELETE THE DATA WE COLLECT FROM YOU?</h2>
<p>You have the right to request access to the personal information we collect from you, correct inaccuracies, or delete your personal information. To request to review, update, or delete your personal information, please visit: <a href="http://voxura.local/profile" target="_blank">http://voxura.local/profile</a></p>
</div>',
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
