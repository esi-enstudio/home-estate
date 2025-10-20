<?php

// config/forms.php

return [

    'property_fields' => [

        // সাধারণ ফিল্ড যা প্রায় সব ধরনের প্রোপার্টির জন্য প্রযোজ্য
        'common' => [
            'core_info', 'specifications', 'location', 'pricing', 'media', 'rules'
        ],

        // প্রোপার্টির ধরন অনুযায়ী নির্দিষ্ট ফিল্ড গ্রুপ (এখন slug ব্যবহার করা হচ্ছে)
        'by_type' => [
            'apartment'        => ['core_info', 'pricing', 'apartment_specs', 'location', 'media', 'rules'],
            'duplex'           => ['core_info', 'pricing', 'apartment_specs', 'location', 'media', 'rules'],
            'penthouse'        => ['core_info', 'pricing', 'apartment_specs', 'location', 'media', 'rules'],
            'studio'           => ['core_info', 'pricing', 'apartment_specs', 'location', 'media', 'rules'],
            'commercial-space' => ['core_info', 'pricing', 'commercial_specs', 'location', 'media'],
            'warehouse'        => ['core_info', 'pricing', 'commercial_specs', 'location', 'media'],
            'shop'             => ['core_info', 'pricing', 'commercial_specs', 'location', 'media'],
            'land'             => ['core_info', 'pricing', 'land_specs', 'location'], // এটি একটি উদাহরণ, প্রয়োজনে যোগ করুন
        ],

        // প্রতিটি ফিল্ড গ্রুপের অধীনে কোন কোন ইনপুট ফিল্ড থাকবে তার তালিকা
        'field_groups' => [
            'core_info'         => ['title', 'description', 'purpose'],
            'pricing'           => ['rent_price', 'rent_type', 'service_charge', 'security_deposit', 'is_negotiable', 'available_from'],
            'apartment_specs'   => ['bedrooms', 'bathrooms', 'balconies', 'size_sqft', 'floor_level', 'total_floors', 'facing_direction', 'year_built', 'additional_features', 'amenities'],
            'commercial_specs'  => ['size_sqft', 'floor_level', 'total_floors', 'year_built', 'additional_features', 'amenities'],
            'land_specs'        => ['size_sqft'],
            'location'          => ['division_id', 'district_id', 'upazila_id', 'union_id', 'address_area', 'address_street', 'address_zipcode', 'google_maps_location_link', 'latitude', 'longitude'],
            'media'             => ['video_url', 'photos'], // 'photos' একটি উদাহরণ, আপনি ছবি আপলোডের জন্য এটি ব্যবহার করতে পারেন
            'rules'             => ['house_rules', 'faqs'],
        ]
    ],
];
