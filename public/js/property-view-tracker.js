// DOMContentLoaded নিশ্চিত করে যে HTML পুরোপুরি লোড হওয়ার পরেই আমাদের কোডটি চলবে।
document.addEventListener('DOMContentLoaded', function() {

    // ১. প্রথমে আমরা সেই এলিমেন্টটি খুঁজব যা আমাদের প্রয়োজনীয় ডেটা ধারণ করে।
    const propertyPageContainer = document.getElementById('property-details-page');

    // যদি এই এলিমেন্টটি পেজে না পাওয়া যায়, তাহলে স্ক্রিপ্টটি আর কাজ করবে না।
    // এটি নিশ্চিত করে যে এই কোডটি শুধুমাত্র প্রপার্টি ডিটেইলস পেজেই চলবে।
    if (!propertyPageContainer) {
        return;
    }

    // ২. এলিমেন্টের data-* অ্যাট্রিবিউট থেকে প্রয়োজনীয় ভ্যারিয়েবল সেট করা
    const viewIncrementUrl = propertyPageContainer.dataset.viewIncrementUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const viewDelay = 20000; // ২০ সেকেন্ড

    let hasIncremented = false;
    let timerId = null;

    // ৩. মূল ফাংশন যা API কল পাঠাবে
    function incrementViewCount() {
        if (hasIncremented) {
            return;
        }
        hasIncremented = true;

        console.log('20 seconds passed. Firing view count increment.');

        fetch(viewIncrementUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('View count successfully incremented on the server.');
                }
            })
            .catch(error => {
                console.error('Error incrementing view count:', error);
                hasIncremented = false; // ফেইল করলে পুনরায় চেষ্টা করার সুযোগ দেওয়া যেতে পারে
            });
    }

    // ৪. টাইমার শুরু করা
    // যেহেতু এই ফাইলটি শুধুমাত্র ডিটেইলস পেজেই লোড হবে, তাই সরাসরি টাইমার চালু করা যায়।
    timerId = setTimeout(incrementViewCount, viewDelay);
});
