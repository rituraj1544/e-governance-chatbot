<?php

namespace App\Support;

class HinglishCatalogueContent
{
    public static function schemes(): array
    {
        return [
            'aadhaar' => [
                'title' => 'Aadhaar Enrolment aur Updates',
                'description' => 'UIDAI current Aadhaar enrolment aur update services, forms, aur acceptable-document lists publish karta hai. Documents aur steps service aur applicant type par depend karte hain, isliye apni request ke liye UIDAI ki exact guidance check karein. UIDAI Aadhaar Seva Kendras par enrolment aur update services deta hai.',
            ],
            'pan_card' => [
                'title' => 'PAN Card aur PAN Services',
                'description' => 'Income Tax Department current PAN allotment forms aur PAN-related services publish karta hai. 1 April 2026 ya uske baad file ki gayi new PAN allotment application ke liye Department ke mutabik Income Tax Rules, 2026 ke prescribed forms use karein. Existing valid PAN valid rehte hain. Apply karne se pehle current form aur service official page par check karein.',
            ],
            'ration_card' => [
                'title' => 'Ration Card aur State/UT Food Portals',
                'description' => 'National Food Security Act ke under State ya Union Territory government eligible households identify karti hai aur AAY aur Priority Household ration cards issue karti hai. Eligibility criteria aur application process har State/UT mein alag ho sakte hain. NFSA apply page par apna State/UT Food Portal choose karein; ek hi national document list ya process assume na karein.',
            ],
            'pm_kisan' => [
                'title' => 'PM-KISAN Samman Nidhi',
                'description' => 'PM-KISAN ek Central Sector scheme hai jo scheme ki exclusion categories ke adheen eligible landholding farmer families ko har saal ₹6,000 teen barabar instalments mein deti hai. State/UT governments eligible families identify karti hain. Registration, beneficiary status, aur current e-KYC information ke liye official portal check karein.',
            ],
            'scholarship' => [
                'title' => 'NSP par Government Scholarships',
                'description' => 'National Scholarship Portal par participating scholarship schemes ki alag eligibility, FAQs, aur application timelines milti hain. Conditions, required details, aur dates har scholarship ke liye same nahi hoti. Apply karne se pehle NSP par selected scheme ki current guidelines check karein.',
            ],
            'general_scheme' => [
                'title' => 'National Portal par Government Services',
                'description' => 'National Portal of India ke Services section mein government services search aur category ke hisaab se browse kar sakte hain. Wahan se apni service ke linked ministry, department, ya State/UT portal par jayein. Requirements aur processing responsible department decide karta hai.',
            ],
            'passport' => [
                'title' => 'Passport Seva',
                'description' => 'Ministry of External Affairs ka Passport Seva portal fresh passport aur reissue applications ki process batata hai. Published process mein online registration, application complete karke submit karna, applicable service fee pay karna, appointment book karna, aur listed documents ke saath PSK ya POPSK visit karna shamil hai. Fees aur document list service par depend karte hain; current advisory check karein.',
            ],
            'voter_services' => [
                'title' => 'ECI Voter Citizen Services',
                'description' => 'Election Commission of India ke Citizen Service Portal par new voter registration, electoral roll search, application tracking, aur correction/update requests milti hain. Apni request se match karta form choose karein aur portal ke current instructions padhein.',
            ],
            'digilocker' => [
                'title' => 'DigiLocker Digital Documents',
                'description' => 'DigiLocker par participating issuers ke available digital documents access, retrieve, share, aur verify kiye ja sakte hain. Koi particular document available hai ya nahi, yeh uske issuer par depend karta hai. Specific service ke liye receiving authority ke rules check karein.',
            ],
            'umang' => [
                'title' => 'UMANG Government Services',
                'description' => 'UMANG, MeitY/NeGD ka platform hai jahan website aur app se participating Central, State, aur local e-Government services access ki ja sakti hain. Available services UMANG par check karein; eligibility aur transactions ke liye service owner ke instructions follow karein.',
            ],
        ];
    }

    public static function faqs(): array
    {
        return [
            'Do I need a new PAN because of PAN 2.0?' => [
                'question' => 'Kya PAN 2.0 ke liye naya PAN lena padega?',
                'answer' => 'Nahi. Government ke PAN 2.0 FAQ ke mutabik existing PAN holders ko upgraded system ke liye naya PAN apply karne ki zaroorat nahi hai, aur existing valid PAN valid rehte hain.',
            ],
            'Which form should I use for a new PAN application?' => [
                'question' => 'New PAN application ke liye kaunsa form use karun?',
                'answer' => '1 April 2026 ya uske baad file ki gayi new PAN allotment application ke liye Income Tax Department, Income Tax Rules, 2026 mein prescribed forms use karne ko kehta hai. Apply karne se pehle Department ka current forms page check karein; purane form number par rely na karein.',
            ],
            'How do I apply for a ration card?' => [
                'question' => 'Ration card ke liye apply kahan aur kaise karun?',
                'answer' => 'Ration card issue karna aur beneficiaries identify karna State/UT government ka kaam hai. NFSA apply page kholkar apna State/UT Food Portal select karein aur uske current instructions follow karein. India bhar mein process same nahi hota.',
            ],
            'Are ration-card eligibility rules and documents the same in every State?' => [
                'question' => 'Kya ration card ki eligibility aur documents har State mein same hote hain?',
                'answer' => 'Nahi. NFSA ke mutabik har State/UT eligible households identify karta hai aur apne criteria banata hai. Eligibility, documents list, aur process ke liye apna State/UT Food Portal check karein; ek universal checklist nahi hai.',
            ],
            'How much support does PM-KISAN provide?' => [
                'question' => 'PM-KISAN mein kitni financial support milti hai?',
                'answer' => 'Official PM-KISAN portal ke mutabik eligible landholding farmer families ko saal ke ₹6,000 teen equal instalments mein milte hain. Scheme exclusions apply hote hain aur eligible families State/UT governments identify karti hain.',
            ],
            'How can I check PM-KISAN status and e-KYC information?' => [
                'question' => 'PM-KISAN status aur e-KYC details kaise check karun?',
                'answer' => 'Beneficiary details ke liye PM-KISAN portal par Know Your Status service use karein. Portal abhi registered farmers ke liye e-KYC mandatory batata hai; apne case ka current status aur available method portal par check karein.',
            ],
            'Where can I check scholarship schemes and eligibility?' => [
                'question' => 'Scholarship schemes aur eligibility kahan check karun?',
                'answer' => 'National Scholarship Portal par schemes ki apni specifications, FAQs, eligibility, aur timelines listed hain. NSP par specific scheme open karke uski current guidelines padhein; har scholarship ki eligibility same nahi hoti.',
            ],
            'Does OTR registration mean my scholarship application is submitted?' => [
                'question' => 'Kya OTR registration se scholarship application submit ho jati hai?',
                'answer' => 'Nahi. NSP par One Time Registration zaroori hai, lekin OTR generate karna apne aap scholarship application submit karna nahi hota. Scheme ki application window open hone par uske liye alag se apply karein aur us scheme ke instructions follow karein.',
            ],
            'Which documents are needed for an Aadhaar update?' => [
                'question' => 'Aadhaar update ke liye kaun se documents chahiye?',
                'answer' => 'Acceptable supporting documents is baat par depend karte hain ki kaunsi detail enrol ya update karni hai. Exact service ke liye UIDAI ki current acceptable-document list aur guidance use karein; ek universal document checklist nahi hai.',
            ],
            'Where can I enrol for Aadhaar or update Aadhaar details?' => [
                'question' => 'Aadhaar enrolment ya details update kahan karwa sakta hoon?',
                'answer' => 'UIDAI ke Aadhaar Seva Kendra page par enrolment aur update services ke saath centre aur appointment information milti hai. Jaane se pehle apni service aur centre ki current details check karein.',
            ],
            'What is the official process for a fresh or reissue passport?' => [
                'question' => 'Fresh ya reissue passport ka official process kya hai?',
                'answer' => 'Passport Seva ke published process mein online registration, application complete karke submit karna, applicable fee pay karna, appointment schedule karna, aur listed documents ke saath PSK/POPSK visit karna shamil hai. Current document advisory aur fee calculator check karein; requirements service ke hisaab se badalti hain.',
            ],
            'Where do I register as a voter or correct voter details?' => [
                'question' => 'Voter registration ya voter details correct kahan karun?',
                'answer' => 'Election Commission of India ka Citizen Service Portal use karein. Wahan new registration, electoral roll search, application tracking, aur correction/update requests milti hain. Apni request ke hisaab se form select karein aur current instructions padhein.',
            ],
            'What can I do with DigiLocker?' => [
                'question' => 'DigiLocker par kya kar sakta hoon?',
                'answer' => 'DigiLocker participating issuers ke available digital documents access, retrieve, share, aur verify karne deta hai. Koi specific document available hoga ya nahi, yeh issuer par depend karta hai; kisi service ke liye receiving authority ke rules check karein.',
            ],
            'What is UMANG and where can I find its services?' => [
                'question' => 'UMANG kya hai aur iski services kahan milengi?',
                'answer' => 'UMANG, MeitY/NeGD ka platform hai jahan website aur app se participating Central, State, aur local e-Government services access hoti hain. Available services UMANG par check karein; eligibility ya transactions ke liye service owner ke instructions follow karein.',
            ],
            'Is this an official government website?' => [
                'question' => 'Kya yeh official government website hai?',
                'answer' => 'Nahi. SevaSetu ek informational academic project hai. Yeh applications submit, identity verify, ya benefits approve nahi karta aur kisi government department ko represent nahi karta. Current requirements aur decisions ke liye linked official source use karein.',
            ],
        ];
    }
}
