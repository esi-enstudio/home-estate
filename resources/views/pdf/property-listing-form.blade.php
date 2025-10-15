<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>প্রপার্টি লিস্টিং ফর্ম</title>
    <style>
        body { font-family: 'kalpurush', sans-serif; line-height: 1.6; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 0; font-size: 14px; }
        .section-title { background-color: #f2f2f2; padding: 10px; font-size: 16px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; }
        .form-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .form-table td { border: 1px solid #ddd; padding: 8px; font-size: 14px; }
        .form-table .label { font-weight: bold; width: 25%; }
        .form-table .value { width: 75%; }
        .checkbox { display: inline-block; width: 20px; height: 20px; border: 1px solid #333; margin-right: 10px; }
        .signature-line { border-top: 1px solid #333; margin-top: 50px; width: 300px; }
        .footer { text-align: center; margin-top: 40px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Home Estate</h1>
        <p>প্রপার্টি লিস্টিং ফর্ম</p>
    </div>

    <div class="section-title">১. মালিকের তথ্য (Owner's Information)</div>
    <table class="form-table">
        <tr><td class="label">মালিকের নাম:</td><td class="value"></td></tr>
        <tr><td class="label">ফোন নম্বর:</td><td class="value"></td></tr>
        <tr><td class="label">ইমেইল অ্যাড্রেস:</td><td class="value"></td></tr>
    </table>

    <div class="section-title">২. মূল তথ্য (Core Information)</div>
    <table class="form-table">
        <tr><td class="label">বিজ্ঞাপনের শিরোনাম:</td><td class="value"></td></tr>
        <tr><td class="label">উদ্দেশ্য:</td><td class="value"><span class="checkbox"></span> ভাড়া &nbsp;&nbsp;&nbsp; <span class="checkbox"></span> বিক্রয়</td></tr>
        <tr><td class="label" style="vertical-align: top;">বিস্তারিত বর্ণনা:</td><td class="value" style="height: 100px;"></td></tr>
    </table>

    <div class="section-title">৩. মূল্য নির্ধারণ (Pricing Details)</div>
    <table class="form-table">
        <tr><td class="label">ভাড়া / মূল্য (৳):</td><td class="value"></td></tr>
        <tr><td class="label">ভাড়ার ধরণ:</td><td class="value"><span class="checkbox"></span> দৈনিক &nbsp;<span class="checkbox"></span> সাপ্তাহিক &nbsp;<span class="checkbox"></span> মাসিক &nbsp;<span class="checkbox"></span> বাৎসরিক</td></tr>
        <tr><td class="label">আলোচনা সাপেক্ষ?</td><td class="value"><span class="checkbox"></span> হ্যাঁ &nbsp;&nbsp;&nbsp; <span class="checkbox"></span> না (একদাম)</td></tr>
        <tr><td class="label">সার্ভিস চার্জ (৳):</td><td class="value"></td></tr>
        <tr><td class="label">সিকিউরিটি ডিপোজিট (৳):</td><td class="value"></td></tr>
        <tr><td class="label">কবে থেকে পাওয়া যাবে:</td><td class="value"></td></tr>
    </table>

    <div class="section-title">৪. প্রপার্টির স্পেসিফিকেশন (Property Specifications)</div>
    <table class="form-table">
        <tr>
            <td class="label">বেডরুম সংখ্যা:</td><td class="value" style="width: 25%;"></td>
            <td class="label">ফ্লোর লেভেল:</td><td class="value" style="width: 25%;"></td>
        </tr>
        <tr>
            <td class="label">বাথরুম সংখ্যা:</td><td class="value"></td>
            <td class="label">মোট তলা:</td><td class="value"></td>
        </tr>
        <tr>
            <td class="label">বারান্দা সংখ্যা:</td><td class="value"></td>
            <td class="label">কোনমুখী:</td><td class="value"></td>
        </tr>
        <tr>
            <td class="label">আকার (স্কয়ার ফিট):</td><td class="value"></td>
            <td class="label">নির্মাণ সাল:</td><td class="value"></td>
        </tr>
    </table>

    <div class="section-title">৫. অবস্থান (Location Details)</div>
    <table class="form-table">
        <tr><td class="label">বিভাগ ও জেলা:</td><td class="value"></td></tr>
        <tr><td class="label">উপজেলা ও ইউনিয়ন:</td><td class="value"></td></tr>
        <tr><td class="label">এলাকা (যেমন: ধানমন্ডি):</td><td class="value"></td></tr>
        <tr><td class="label">বাসা ও রাস্তার ঠিকানা:</td><td class="value"></td></tr>
        <tr><td class="label">পোস্ট কোড:</td><td class="value"></td></tr>
    </table>

    <p style="page-break-before: always;"></p> {{-- নতুন পেজে যাওয়ার জন্য --}}

    <div class="section-title">৬. অতিরিক্ত সুবিধা ও নিয়মাবলী (Additional Features & Rules)</div>
    <table class="form-table">
        <tr><td class="label" style="vertical-align: top;">অতিরিক্ত সুবিধা:</td><td class="value" style="height: 80px;"></td></tr>
        <tr><td class="label" style="vertical-align: top;">বাসার নিয়মাবলী:</td><td class="value" style="height: 80px;"></td></tr>
    </table>

    <div class="section-title">৭. ছবি ও ভিডিও (Media Submission)</div>
    <table class="form-table">
        <tr><td class="label">ভিডিও লিংক:</td><td class="value"></td></tr>
        <tr><td colspan="2" style="text-align: center;">ছবিগুলো অনুগ্রহ করে <strong>photos@homeestate.com</strong> ইমেইলে অথবা <strong>+8801xxxxxxxxx</strong> নম্বরে হোয়াটসঅ্যাপ করুন।</td></tr>
    </table>

    <div style="margin-top: 100px;">
        <p>আমি এই মর্মে ঘোষণা করছি যে, উপরে প্রদত্ত সকল তথ্য আমার জ্ঞান ও বিশ্বাসমতে সম্পূর্ণ সত্য।</p>
        <div class="signature-line"></div>
        <p style="margin-top: 5px;">মালিকের স্বাক্ষর</p>
    </div>

    <div class="footer">
        Home Estate | আপনার স্বপ্নের ঠিকানা | www.home-estate.com
    </div>
</div>
</body>
</html>
