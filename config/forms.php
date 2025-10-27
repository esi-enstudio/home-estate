<?php

use Illuminate\Support\Str;

return [

    'property_fields' => [

        // প্রোপার্টির ধরন অনুযায়ী নির্দিষ্ট ফিল্ড গ্রুপ (slug ব্যবহার করে)
        'by_type' => [
            // --- Residential ---
            'apartment' => ['core_info', 'apartment_specs', 'location', 'pricing', 'media', 'rules'],
            'flat' => ['core_info', 'apartment_specs', 'location', 'pricing', 'media', 'rules'],
            'studio-apartment' => ['core_info', 'apartment_specs', 'location', 'pricing', 'media', 'rules'],
            'duplex' => ['core_info', 'apartment_specs', 'location', 'pricing', 'media', 'rules'],
            'penthouse' => ['core_info', 'apartment_specs', 'location', 'pricing', 'media', 'rules'],
            'shared-room' => ['core_info', 'shared_room_specs', 'location', 'pricing', 'media', 'rules'],
            'bachelor-room' => ['core_info', 'shared_room_specs', 'location', 'pricing', 'media', 'rules'],
            'family-apartment' => ['core_info', 'apartment_specs', 'location', 'pricing', 'media', 'rules'],
            'sublet' => ['core_info', 'shared_room_specs', 'location', 'pricing', 'media', 'rules'],
            'house' => ['core_info', 'apartment_specs', 'location', 'pricing', 'media', 'rules'],
            'villa' => ['core_info', 'apartment_specs', 'location', 'pricing', 'media', 'rules'],
            'cottage' => ['core_info', 'apartment_specs', 'location', 'pricing', 'media', 'rules'],
            'mess' => ['core_info', 'shared_room_specs', 'location', 'pricing', 'media', 'rules'],

            // --- Commercial ---
            'office-space' => ['core_info', 'commercial_specs', 'location', 'pricing', 'media'],
            'shop' => ['core_info', 'commercial_specs', 'location', 'pricing', 'media'],
            'showroom' => ['core_info', 'commercial_specs', 'location', 'pricing', 'media'],
            'warehouse' => ['core_info', 'commercial_specs', 'location', 'pricing', 'media'],
            'factory' => ['core_info', 'commercial_specs', 'location', 'pricing', 'media'],
            'restaurant-space' => ['core_info', 'commercial_specs', 'location', 'pricing', 'media'],
            'cafe-space' => ['core_info', 'commercial_specs', 'location', 'pricing', 'media'],
            'coaching-center' => ['core_info', 'commercial_specs', 'location', 'pricing', 'media'],
            'beauty-salon' => ['core_info', 'commercial_specs', 'location', 'pricing', 'media'],
            'gym-space' => ['core_info', 'commercial_specs', 'location', 'pricing', 'media'],
            'community-hall' => ['core_info', 'commercial_specs', 'location', 'pricing', 'media'],

            // --- Land & Plot ---
            'residential-plot' => ['core_info', 'land_specs', 'location', 'pricing'],
            'commercial-plot' => ['core_info', 'land_specs', 'location', 'pricing'],
            'agricultural-land' => ['core_info', 'land_specs', 'location', 'pricing'],
            'industrial-land' => ['core_info', 'land_specs', 'location', 'pricing'],

            // --- Short-term / Hospitality ---
            'guest-house' => ['core_info', 'hospitality_specs', 'location', 'pricing', 'media', 'rules'],
            'hostel' => ['core_info', 'hospitality_specs', 'location', 'pricing', 'media', 'rules'],
            'hotel-room' => ['core_info', 'hospitality_specs', 'location', 'pricing', 'media', 'rules'],
            'resort' => ['core_info', 'hospitality_specs', 'location', 'pricing', 'media', 'rules'],
            'serviced-apartment' => ['core_info', 'hospitality_specs', 'location', 'pricing', 'media', 'rules'],

            // --- Parking & Others ---
            'garage' => ['core_info', 'storage_specs', 'location', 'pricing', 'media'],
            'car-parking-space' => ['core_info', 'storage_specs', 'location', 'pricing'],
            'storage-room' => ['core_info', 'storage_specs', 'location', 'pricing', 'media'],
            'rooftop-space' => ['core_info', 'storage_specs', 'location', 'pricing'],
            'basement-space' => ['core_info', 'storage_specs', 'location', 'pricing', 'media'],
        ],

        // প্রতিটি ফিল্ড গ্রুপের অধীনে কোন কোন ইনপুট ফিল্ড থাকবে তার তালিকা
        'field_groups' => [
            'core_info' => ['title', 'description', 'purpose', 'tenant_types'],

            'pricing' => ['rent_price', 'rent_type', 'service_charge', 'security_deposit', 'is_negotiable', 'available_from'],

            'apartment_specs' => ['bedrooms', 'bathrooms', 'balconies', 'size_sqft', 'floor_level', 'total_floors', 'facing_direction', 'year_built', 'additional_features', 'amenities'],

            'shared_room_specs' => ['bedrooms', 'bathrooms', 'size_sqft', 'floor_level', 'additional_features', 'amenities'],

            'commercial_specs' => ['size_sqft', 'floor_level', 'total_floors', 'year_built', 'additional_features', 'amenities'],

            'hospitality_specs' => ['bedrooms', 'bathrooms', 'size_sqft', 'amenities'],

            'land_specs' => ['size_sqft'],

            'storage_specs' => ['size_sqft'],

            'location' => ['division_id', 'district_id', 'upazila_id', 'union_id', 'address_area', 'address_street', 'address_zipcode', 'google_maps_location_link', 'latitude', 'longitude'],

            'media' => ['thumbnail', 'gallery', 'video_url'],

            'rules' => ['house_rules', 'faqs'],
        ]
    ],
];
