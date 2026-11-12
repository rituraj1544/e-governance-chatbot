<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Scheme;
use App\Support\HinglishCatalogueContent;
use Illuminate\Database\Seeder;

class SchemeFaqSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'key' => 'aadhaar', 'category' => 'aadhaar',
                'url' => 'https://uidai.gov.in/en/enrolment-and-updates',
                'keywords' => ['aadhaar', 'aadhar', 'uidai', 'aadhaar card', 'aadhaar update', 'enrolment', 'आधार', 'आधार कार्ड', 'आधार अपडेट', 'aadhaar me sudhar', 'aadhar update'],
                'en' => [
                    'title' => 'Aadhaar Enrolment and Updates',
                    'description' => 'UIDAI publishes the current Aadhaar enrolment and update services, forms, and acceptable-document lists. The documents and steps depend on the service and applicant type, so check UIDAI guidance for the exact request. UIDAI Aadhaar Seva Kendras offer enrolment and update services.',
                ],
                'hi' => [
                    'title' => 'आधार नामांकन और अपडेट',
                    'description' => 'UIDAI आधार नामांकन और अपडेट सेवाओं, फॉर्म तथा स्वीकार्य दस्तावेज़ों की वर्तमान जानकारी देता है। दस्तावेज़ और चरण सेवा तथा आवेदक के प्रकार के अनुसार बदल सकते हैं, इसलिए अपने अनुरोध के लिए UIDAI की सूची देखें। UIDAI आधार सेवा केंद्र नामांकन और अपडेट सेवाएँ देते हैं।',
                ],
            ],
            [
                'key' => 'pan_card', 'category' => 'pan_card',
                'url' => 'https://www.incometax.gov.in/iec/foportal/help/all-topics/e-filing-services/income-tax-forms',
                'keywords' => ['pan', 'pan card', 'pan-card', 'permanent account number', 'pan 2.0', 'new pan', 'पैन', 'पैन कार्ड', 'pan banwana', 'pan apply', 'pan application'],
                'en' => [
                    'title' => 'PAN Card and PAN Services',
                    'description' => 'The Income Tax Department publishes current PAN allotment forms and PAN-related services. For a new PAN allotment application filed on or after 1 April 2026, the Department says to use the forms prescribed under the Income Tax Rules, 2026. Existing valid PANs remain valid; check the official page for the current form and service before applying.',
                ],
                'hi' => [
                    'title' => 'पैन कार्ड और पैन सेवाएँ',
                    'description' => 'आयकर विभाग पैन आवंटन के वर्तमान फॉर्म और पैन से जुड़ी सेवाओं की जानकारी देता है। 1 अप्रैल 2026 या उसके बाद नए पैन के लिए आवेदन करते समय विभाग के अनुसार आयकर नियम, 2026 में निर्धारित फॉर्म इस्तेमाल करें। मौजूदा वैध पैन वैध रहते हैं; आवेदन से पहले वर्तमान फॉर्म और सेवा की आधिकारिक जानकारी देखें।',
                ],
            ],
            [
                'key' => 'ration_card', 'category' => 'ration_card',
                'url' => 'https://nfsa.gov.in/portal/apply',
                'keywords' => ['ration card', 'ration', 'rashan card', 'rashan', 'food security', 'nfsa', 'राशन कार्ड', 'राशन', 'rashan card ke liye', 'ration card apply'],
                'en' => [
                    'title' => 'Ration Card and State/UT Food Portals',
                    'description' => 'Under the National Food Security Act, the State or Union Territory government identifies eligible households and handles AAY and Priority Household ration-card issuance. Eligibility criteria and application procedures are state-specific. Use the NFSA apply page to select your State/UT food portal; do not assume a single national document list or process.',
                ],
                'hi' => [
                    'title' => 'राशन कार्ड और राज्य/केंद्रशासित प्रदेश पोर्टल',
                    'description' => 'राष्ट्रीय खाद्य सुरक्षा अधिनियम के अंतर्गत पात्र परिवारों की पहचान और AAY तथा प्राथमिकता परिवार राशन कार्ड जारी करने का काम संबंधित राज्य या केंद्रशासित प्रदेश सरकार करती है। पात्रता और आवेदन प्रक्रिया राज्य के अनुसार अलग होती है। NFSA के आवेदन पृष्ठ से अपना राज्य/केंद्रशासित प्रदेश चुनें; एक ही राष्ट्रीय दस्तावेज़ सूची या प्रक्रिया मानकर न चलें।',
                ],
            ],
            [
                'key' => 'pm_kisan', 'category' => 'pm_kisan',
                'url' => 'https://pmkisan.gov.in/',
                'keywords' => ['pm kisan', 'pm-kisan', 'pmkisan', 'pm kisaan', 'kisan samman nidhi', 'pm kisan status', 'पीएम किसान', 'पीएम-किसान', 'किसान सम्मान निधि', 'pm kisan ka paisa'],
                'en' => [
                    'title' => 'PM-KISAN Samman Nidhi',
                    'description' => 'PM-KISAN is a Central Sector scheme that provides ₹6,000 per year in three equal instalments to eligible landholding farmer families, subject to the scheme’s exclusion categories. State/UT governments identify eligible families. The official portal provides registration, beneficiary-status, and current e-KYC information; check it for your case.',
                ],
                'hi' => [
                    'title' => 'पीएम-किसान सम्मान निधि',
                    'description' => 'पीएम-किसान केंद्र क्षेत्र की योजना है। योजना की अपवर्जन श्रेणियों के अधीन पात्र भूमिधारक किसान परिवारों को सालाना ₹6,000 तीन समान किस्तों में दिए जाते हैं। पात्र परिवारों की पहचान राज्य/केंद्रशासित प्रदेश सरकारें करती हैं। पंजीकरण, लाभार्थी स्थिति और वर्तमान e-KYC जानकारी के लिए आधिकारिक पोर्टल देखें।',
                ],
            ],
            [
                'key' => 'scholarship', 'category' => 'scholarship',
                'url' => 'https://scholarships.gov.in/Students',
                'keywords' => ['scholarship', 'scholarships', 'national scholarship portal', 'nsp', 'student aid', 'chhatravritti', 'छात्रवृत्ति', 'स्कॉलरशिप', 'scholarship kaise milegi'],
                'en' => [
                    'title' => 'Government Scholarships on NSP',
                    'description' => 'The National Scholarship Portal lists participating scholarship schemes with scheme-specific eligibility, FAQs, and application timelines. Conditions, required details, and dates are not the same for every scholarship. Check the selected scheme’s current guidelines on NSP before applying.',
                ],
                'hi' => [
                    'title' => 'NSP पर सरकारी छात्रवृत्तियाँ',
                    'description' => 'राष्ट्रीय छात्रवृत्ति पोर्टल पर उपलब्ध छात्रवृत्ति योजनाओं की योजना-विशिष्ट पात्रता, FAQ और आवेदन समय-सारणी दी जाती है। हर छात्रवृत्ति की शर्तें, आवश्यक जानकारी और तारीखें अलग हो सकती हैं। आवेदन से पहले NSP पर चुनी गई योजना के वर्तमान दिशा-निर्देश देखें।',
                ],
            ],
            [
                'key' => 'general_scheme', 'category' => 'general_scheme',
                'url' => 'https://www.india.gov.in/services',
                'keywords' => ['government services', 'government service', 'govt services', 'government scheme', 'government schemes', 'scheme', 'schemes', 'yojana', 'national portal', 'india.gov.in', 'central service', 'state service', 'सरकारी सेवाएँ', 'सरकारी सेवा', 'सरकारी योजना', 'योजना', 'भारत सरकार पोर्टल', 'sarkari seva'],
                'en' => [
                    'title' => 'Find Government Services on the National Portal',
                    'description' => 'The National Portal of India has a Services section for searching government services and browsing them by category. Start there, then follow the linked ministry, department, or State/UT portal for the service you need. Requirements and processing are set by the responsible department.',
                ],
                'hi' => [
                    'title' => 'राष्ट्रीय पोर्टल पर सरकारी सेवाएँ खोजें',
                    'description' => 'भारत के राष्ट्रीय पोर्टल पर सरकारी सेवाएँ खोजने और श्रेणी के अनुसार देखने का Services अनुभाग है। वहाँ से शुरू करें और अपनी सेवा के लिए संबंधित मंत्रालय, विभाग या राज्य/केंद्रशासित प्रदेश पोर्टल खोलें। आवश्यकताएँ और प्रक्रिया संबंधित विभाग तय करता है।',
                ],
            ],
            [
                'key' => 'passport', 'category' => 'general_scheme',
                'url' => 'https://passportindia.gov.in/psp/apply',
                'keywords' => ['passport', 'passport seva', 'passport application', 'fresh passport', 'passport reissue', 'passport appointment', 'पासपोर्ट', 'पासपोर्ट सेवा', 'passport kaise banaye'],
                'en' => [
                    'title' => 'Passport Seva',
                    'description' => 'The Ministry of External Affairs Passport Seva portal explains fresh-passport and reissue applications. Its published process includes online registration, completing and submitting the application, paying the applicable service fee, booking an appointment, and visiting a PSK or POPSK with the documents listed for that service. Fees and document lists depend on the service; check the current official advisory.',
                ],
                'hi' => [
                    'title' => 'पासपोर्ट सेवा',
                    'description' => 'विदेश मंत्रालय का पासपोर्ट सेवा पोर्टल नए पासपोर्ट और पुनः जारी करने की प्रक्रिया बताता है। प्रकाशित प्रक्रिया में ऑनलाइन पंजीकरण, आवेदन भरना और जमा करना, लागू सेवा शुल्क देना, अपॉइंटमेंट लेना तथा सेवा के लिए सूचीबद्ध दस्तावेज़ों के साथ PSK या POPSK जाना शामिल है। शुल्क और दस्तावेज़ सेवा के अनुसार बदलते हैं; वर्तमान आधिकारिक जानकारी देखें।',
                ],
            ],
            [
                'key' => 'voter_services', 'category' => 'general_scheme',
                'url' => 'https://voters.eci.gov.in/',
                'keywords' => ['voter', 'voter id', 'voter registration', 'voter card', 'electoral roll', 'epic', 'eci', 'मतदाता', 'वोटर आईडी', 'मतदाता सूची', 'matdata', 'vote registration'],
                'en' => [
                    'title' => 'Election Commission Voter Services',
                    'description' => 'The Election Commission of India’s citizen portal supports new voter registration, electoral-roll search, application tracking, and requests to correct or update entries. The form depends on the request, so use the portal’s current form guidance rather than a general checklist.',
                ],
                'hi' => [
                    'title' => 'निर्वाचन आयोग की मतदाता सेवाएँ',
                    'description' => 'भारत निर्वाचन आयोग का नागरिक पोर्टल नया मतदाता पंजीकरण, मतदाता सूची में नाम खोजना, आवेदन की स्थिति देखना और प्रविष्टि में सुधार या अपडेट के अनुरोध जैसी सेवाएँ देता है। अनुरोध के अनुसार फॉर्म बदलता है; पोर्टल के वर्तमान निर्देश देखें।',
                ],
            ],
            [
                'key' => 'digilocker', 'category' => 'general_scheme',
                'url' => 'https://www.digilocker.gov.in/',
                'keywords' => ['digilocker', 'digi locker', 'digital locker', 'digital documents', 'document wallet', 'डिजिलॉकर', 'डिजिटल दस्तावेज़', 'digilocker document'],
                'en' => [
                    'title' => 'DigiLocker Digital Documents',
                    'description' => 'DigiLocker is a MeitY initiative that gives citizens access to digital documents issued by participating organizations and supports retrieving, sharing, and verifying them. Availability depends on the issuing organization. Check the official DigiLocker service and the receiving department’s rules for a specific application.',
                ],
                'hi' => [
                    'title' => 'डिजिलॉकर के डिजिटल दस्तावेज़',
                    'description' => 'डिजिलॉकर MeitY की पहल है, जो नागरिकों को भाग लेने वाले जारीकर्ता संगठनों के डिजिटल दस्तावेज़ देखने, प्राप्त करने, साझा करने और सत्यापित करने की सुविधा देती है। दस्तावेज़ की उपलब्धता जारीकर्ता संगठन पर निर्भर करती है। किसी आवेदन के लिए डिजिलॉकर और संबंधित विभाग के नियम देखें।',
                ],
            ],
            [
                'key' => 'umang', 'category' => 'general_scheme',
                'url' => 'https://www.umang.gov.in/landing/aboutus',
                'keywords' => ['umang', 'umang app', 'umang services', 'unified mobile application', 'उमंग', 'उमंग ऐप', 'umang me seva'],
                'en' => [
                    'title' => 'UMANG Government Services App',
                    'description' => 'UMANG is developed by MeitY and NeGD as a single platform for accessing participating Central, State, and local e-Government services through its website and app. Each service’s information and transaction rules remain with the responsible department. Check UMANG for service availability and the linked department for requirements.',
                ],
                'hi' => [
                    'title' => 'उमंग सरकारी सेवा ऐप',
                    'description' => 'UMANG को MeitY और NeGD ने केंद्र, राज्य तथा स्थानीय ई-सरकारी सेवाओं तक पहुँच के लिए एक मंच के रूप में विकसित किया है। प्रत्येक सेवा की जानकारी और लेन-देन के नियम संबंधित विभाग के पास रहते हैं। सेवा उपलब्धता UMANG पर और आवश्यकताएँ संबंधित विभाग से जाँचें।',
                ],
            ],
        ];

        $hinglishServices = HinglishCatalogueContent::schemes();
        foreach ($services as $service) {
            foreach (['en', 'hi'] as $language) {
                Scheme::updateOrCreate(
                    ['translation_key' => $service['key'], 'language' => $language],
                    [
                        'title' => $service[$language]['title'],
                        'category' => $service['category'],
                        'description' => $service[$language]['description'],
                        'eligibility' => null,
                        'required_documents' => null,
                        'application_process' => null,
                        'title_hinglish' => $language === 'en' ? ($hinglishServices[$service['key']]['title'] ?? null) : null,
                        'description_hinglish' => $language === 'en' ? ($hinglishServices[$service['key']]['description'] ?? null) : null,
                        'eligibility_hinglish' => null,
                        'required_documents_hinglish' => null,
                        'application_process_hinglish' => null,
                        'official_source_url' => $service['url'],
                        'keywords' => $service['keywords'],
                        'is_active' => true,
                    ]
                );
            }
        }

        $faqs = [
            [
                'category' => 'pan_card', 'url' => 'https://static.pib.gov.in/WriteReadData/specificdocs/documents/2024/nov/doc20241127454301.pdf',
                'keywords' => ['existing pan', 'pan 2.0', 'old pan card', 'new pan number', 'purana pan', 'mera pan', 'पुराना पैन', 'पैन 2.0', 'मौजूदा पैन'],
                'en' => ['question' => 'Do I need a new PAN because of PAN 2.0?', 'answer' => 'No. The Government’s PAN 2.0 FAQ says existing PAN holders do not need to apply for a new PAN under the upgraded system, and existing valid PANs remain valid.'],
                'hi' => ['question' => 'क्या PAN 2.0 के कारण नया पैन बनवाना होगा?', 'answer' => 'नहीं। सरकार के PAN 2.0 FAQ के अनुसार मौजूदा पैन धारकों को नई प्रणाली के कारण नया पैन लेने की ज़रूरत नहीं है और मौजूदा वैध पैन वैध रहते हैं।'],
            ],
            [
                'category' => 'pan_card', 'url' => 'https://www.incometax.gov.in/iec/foportal/help/all-topics/e-filing-services/income-tax-forms',
                'keywords' => ['new pan application', 'apply for pan', 'pan form', 'naya pan', 'pan kaise banaye', 'pan ke liye apply', 'नया पैन', 'पैन आवेदन', 'पैन फॉर्म'],
                'en' => ['question' => 'Which form should I use for a new PAN application?', 'answer' => 'For a new PAN allotment application filed on or after 1 April 2026, the Income Tax Department says to use the forms prescribed under the Income Tax Rules, 2026. Check the Department’s current forms page before applying; do not rely on an older form number.'],
                'hi' => ['question' => 'नए पैन आवेदन के लिए कौन सा फॉर्म इस्तेमाल करें?', 'answer' => '1 अप्रैल 2026 या उसके बाद नए पैन आवंटन के आवेदन के लिए आयकर विभाग आयकर नियम, 2026 में निर्धारित फॉर्म इस्तेमाल करने को कहता है। आवेदन से पहले विभाग का वर्तमान फॉर्म पृष्ठ देखें; पुराने फॉर्म नंबर पर निर्भर न रहें।'],
            ],
            [
                'category' => 'ration_card', 'url' => 'https://nfsa.gov.in/portal/apply',
                'keywords' => ['apply ration card', 'new ration card', 'ration card application', 'ration card apply', 'rashan card apply', 'ration card ke liye', 'नया राशन कार्ड', 'राशन कार्ड आवेदन', 'राशन कार्ड के लिए', 'राशन कार्ड बनवाना'],
                'en' => ['question' => 'How do I apply for a ration card?', 'answer' => 'Ration-card issuance and beneficiary identification are handled by the State/UT government. Use the NFSA apply page to open your State/UT Food Portal and follow its current instructions. The process is not identical across India.'],
                'hi' => ['question' => 'राशन कार्ड के लिए आवेदन कहाँ करें?', 'answer' => 'राशन कार्ड जारी करना और लाभार्थियों की पहचान राज्य/केंद्रशासित प्रदेश सरकार करती है। NFSA के आवेदन पृष्ठ से अपना राज्य/केंद्रशासित प्रदेश खाद्य पोर्टल खोलें और उसके वर्तमान निर्देश देखें। पूरे भारत में प्रक्रिया एक जैसी नहीं है।'],
            ],
            [
                'category' => 'ration_card', 'url' => 'https://www.nfsa.gov.in/portal/Ration_Card_State_Portals_',
                'keywords' => ['ration card eligibility', 'ration card documents', 'ration card rules', 'state ration card', 'ration card in my state', 'rashan card niyam', 'राज्य के नियम', 'राशन कार्ड पात्रता', 'राशन कार्ड दस्तावेज़'],
                'en' => ['question' => 'Are ration-card eligibility rules and documents the same in every State?', 'answer' => 'No. NFSA states that each State/UT identifies eligible households and frames its own criteria. Check your State/UT Food Portal for its eligibility, document list, and process; this catalogue cannot give one universal checklist.'],
                'hi' => ['question' => 'क्या हर राज्य में राशन कार्ड की पात्रता और दस्तावेज़ एक जैसे हैं?', 'answer' => 'नहीं। NFSA के अनुसार पात्र परिवारों की पहचान और अपने मानदंड तय करना हर राज्य/केंद्रशासित प्रदेश की जिम्मेदारी है। पात्रता, दस्तावेज़ सूची और प्रक्रिया के लिए अपना खाद्य पोर्टल देखें; यहाँ एक सार्वभौमिक सूची नहीं दी जा सकती।'],
            ],
            [
                'category' => 'pm_kisan', 'url' => 'https://pmkisan.gov.in/',
                'keywords' => ['pm kisan amount', 'pm kisan benefit', 'pm kisan installments', 'pm kisan paisa', 'pm kisan kitna', 'कितना पैसा', 'कितनी किस्त', 'किसान लाभ राशि'],
                'en' => ['question' => 'How much support does PM-KISAN provide?', 'answer' => 'The official PM-KISAN portal describes income support of ₹6,000 per year in three equal instalments for eligible landholding farmer families. Scheme exclusions apply, and State/UT governments identify eligible families.'],
                'hi' => ['question' => 'पीएम-किसान में कितनी सहायता मिलती है?', 'answer' => 'आधिकारिक पीएम-किसान पोर्टल पात्र भूमिधारक किसान परिवारों के लिए सालाना ₹6,000 की सहायता तीन समान किस्तों में बताता है। योजना की अपवर्जन श्रेणियाँ लागू हैं और पात्र परिवारों की पहचान राज्य/केंद्रशासित प्रदेश सरकारें करती हैं।'],
            ],
            [
                'category' => 'pm_kisan', 'url' => 'https://pmkisan.gov.in/',
                'keywords' => ['pm kisan status', 'status', 'kys', 'pm kisan ekyc', 'check pm kisan', 'pm kisan ka status', 'pm kisan e-kyc', 'स्थिति', 'ई-केवाईसी', 'पैसा आया'],
                'en' => ['question' => 'How can I check PM-KISAN status and e-KYC information?', 'answer' => 'Use the PM-KISAN portal’s Know Your Status service for beneficiary details. The portal currently says e-KYC is mandatory for registered farmers; check the portal for the available method and current status for your case.'],
                'hi' => ['question' => 'पीएम-किसान की स्थिति और e-KYC जानकारी कैसे देखें?', 'answer' => 'लाभार्थी की जानकारी के लिए पीएम-किसान पोर्टल की Know Your Status सेवा देखें। पोर्टल वर्तमान में पंजीकृत किसानों के लिए e-KYC अनिवार्य बताता है; अपने मामले की स्थिति और उपलब्ध तरीका पोर्टल पर जाँचें।'],
            ],
            [
                'category' => 'scholarship', 'url' => 'https://scholarships.gov.in/All-Scholarships',
                'keywords' => ['find scholarship', 'scholarship eligibility', 'scholarships on nsp', 'student scholarship scheme', 'scholarship kaise milegi', 'छात्रवृत्ति खोजें', 'कौन सी छात्रवृत्ति', 'छात्रवृत्ति पात्रता'],
                'en' => ['question' => 'Where can I check scholarship schemes and eligibility?', 'answer' => 'The National Scholarship Portal lists schemes with their own specifications, FAQs, eligibility, and timelines. Open the particular scheme on NSP and read its current guidelines; eligibility is not the same for every scholarship.'],
                'hi' => ['question' => 'छात्रवृत्ति योजनाएँ और पात्रता कहाँ देखें?', 'answer' => 'राष्ट्रीय छात्रवृत्ति पोर्टल पर योजनाओं के विनिर्देश, FAQ, पात्रता और समय-सारणी दी जाती है। NSP पर संबंधित योजना खोलकर उसके वर्तमान दिशा-निर्देश पढ़ें; हर छात्रवृत्ति की पात्रता एक जैसी नहीं होती।'],
            ],
            [
                'category' => 'scholarship', 'url' => 'https://scholarships.gov.in/otrapplication/',
                'keywords' => ['otr', 'scholarship application', 'otr application', 'is otr application', 'apply scholarship nsp', 'छात्रवृत्ति आवेदन', 'ओटीआर', 'scholarship form'],
                'en' => ['question' => 'Does OTR registration mean my scholarship application is submitted?', 'answer' => 'No. NSP says One Time Registration is required, but generating an OTR does not itself count as a scholarship application. Apply separately to a scheme when its application window is open and follow that scheme’s instructions.'],
                'hi' => ['question' => 'क्या OTR बनने से छात्रवृत्ति का आवेदन जमा हो जाता है?', 'answer' => 'नहीं। NSP के अनुसार One Time Registration आवश्यक है, लेकिन OTR बनना अपने-आप छात्रवृत्ति आवेदन नहीं है। योजना की आवेदन अवधि खुलने पर अलग से आवेदन करें और उसी योजना के निर्देश मानें।'],
            ],
            [
                'category' => 'aadhaar', 'url' => 'https://uidai.gov.in/en/enrolment-and-updates',
                'keywords' => ['aadhaar update documents', 'aadhaar acceptable documents', 'aadhaar address update document', 'aadhar documents', 'aadhaar me kya update', 'आधार अपडेट दस्तावेज़', 'आधार में सुधार', 'आधार के दस्तावेज़'],
                'en' => ['question' => 'Which documents are needed for an Aadhaar update?', 'answer' => 'The acceptable supporting documents depend on what is being enrolled or updated. Use UIDAI’s current acceptable-document list and guidance for the exact service; this chatbot does not provide a universal document checklist.'],
                'hi' => ['question' => 'आधार अपडेट के लिए कौन से दस्तावेज़ चाहिए?', 'answer' => 'स्वीकार्य दस्तावेज़ इस बात पर निर्भर करते हैं कि कौन-सी जानकारी दर्ज या अपडेट करनी है। संबंधित सेवा के लिए UIDAI की वर्तमान स्वीकार्य दस्तावेज़ सूची और निर्देश देखें; यहाँ एक सार्वभौमिक सूची नहीं दी जाती।'],
            ],
            [
                'category' => 'aadhaar', 'url' => 'https://uidai.gov.in/en/aadhaar-seva-kendra',
                'keywords' => ['aadhaar enrolment centre', 'aadhaar seva kendra', 'where update aadhaar', 'uidai center', 'आधार सेवा केंद्र', 'आधार केंद्र', 'नामांकन केंद्र', 'aadhaar center'],
                'en' => ['question' => 'Where can I enrol for Aadhaar or update Aadhaar details?', 'answer' => 'UIDAI’s Aadhaar Seva Kendra page lists enrolment and update services and offers centre and appointment information. Check the page for the service you need and the current centre details before visiting.'],
                'hi' => ['question' => 'आधार नामांकन या अपडेट कहाँ कराएँ?', 'answer' => 'UIDAI का आधार सेवा केंद्र पृष्ठ नामांकन और अपडेट सेवाएँ तथा केंद्र और अपॉइंटमेंट की जानकारी देता है। जाने से पहले अपनी सेवा और केंद्र का वर्तमान विवरण उस पृष्ठ पर देखें।'],
            ],
            [
                'category' => 'general_scheme', 'url' => 'https://passportindia.gov.in/psp/apply',
                'keywords' => ['passport seva', 'passport apply', 'new passport', 'passport reissue', 'passport appointment', 'passport kaise banaye', 'पासपोर्ट आवेदन', 'पासपोर्ट सेवा', 'नया पासपोर्ट'],
                'en' => ['question' => 'What is the official process for a fresh or reissue passport?', 'answer' => 'Passport Seva’s published process is to register online, complete and submit the application, pay the applicable fee, schedule an appointment, and visit a PSK/POPSK with the documents listed for that service. Check the current document advisory and fee calculator; requirements vary by service.'],
                'hi' => ['question' => 'नए या पुनः जारी पासपोर्ट की आधिकारिक प्रक्रिया क्या है?', 'answer' => 'पासपोर्ट सेवा की प्रकाशित प्रक्रिया में ऑनलाइन पंजीकरण, आवेदन भरना और जमा करना, लागू शुल्क देना, अपॉइंटमेंट लेना तथा सेवा के लिए सूचीबद्ध दस्तावेज़ों के साथ PSK/POPSK जाना शामिल है। वर्तमान दस्तावेज़ निर्देश और शुल्क कैलकुलेटर देखें; आवश्यकताएँ सेवा के अनुसार बदलती हैं।'],
            ],
            [
                'category' => 'general_scheme', 'url' => 'https://voters.eci.gov.in/',
                'keywords' => ['voter registration', 'voter id correction', 'voter card', 'eci voter portal', 'voter list', 'matdata', 'मतदाता पंजीकरण', 'वोटर आईडी', 'वोटर कार्ड', 'मतदाता सूची'],
                'en' => ['question' => 'Where do I register as a voter or correct voter details?', 'answer' => 'Use the Election Commission of India’s Citizen Service Portal. It provides new registration, electoral-roll search, application tracking, and correction/update requests. Select the portal form that matches your request and read its current instructions.'],
                'hi' => ['question' => 'मतदाता पंजीकरण या विवरण में सुधार कहाँ करें?', 'answer' => 'भारत निर्वाचन आयोग का नागरिक सेवा पोर्टल इस्तेमाल करें। वहाँ नया पंजीकरण, मतदाता सूची में नाम खोजना, आवेदन की स्थिति और सुधार/अपडेट के अनुरोध उपलब्ध हैं। अपने अनुरोध के अनुसार फॉर्म चुनें और वर्तमान निर्देश पढ़ें।'],
            ],
            [
                'category' => 'general_scheme', 'url' => 'https://www.digilocker.gov.in/',
                'keywords' => ['digilocker', 'digi locker', 'digital locker', 'digital documents', 'document wallet', 'डिजिलॉकर', 'डिजिटल दस्तावेज़', 'digilocker documents'],
                'en' => ['question' => 'What can I do with DigiLocker?', 'answer' => 'DigiLocker lets citizens access, retrieve, share, and verify digital documents made available by participating issuers. Whether a particular document is available depends on its issuer; check the receiving authority’s rules for a specific service.'],
                'hi' => ['question' => 'डिजिलॉकर से क्या कर सकते हैं?', 'answer' => 'डिजिलॉकर भाग लेने वाले जारीकर्ताओं द्वारा उपलब्ध कराए गए डिजिटल दस्तावेज़ देखने, प्राप्त करने, साझा करने और सत्यापित करने देता है। कोई खास दस्तावेज़ उपलब्ध होगा या नहीं यह जारीकर्ता पर निर्भर करता है; संबंधित सेवा के लिए विभाग के नियम देखें।'],
            ],
            [
                'category' => 'general_scheme', 'url' => 'https://www.umang.gov.in/landing/aboutus',
                'keywords' => ['umang app', 'umang services', 'umang government', 'umang me seva', 'उमंग ऐप', 'उमंग सेवाएँ', 'umang'],
                'en' => ['question' => 'What is UMANG and where can I find its services?', 'answer' => 'UMANG is a MeitY/NeGD platform for accessing participating Central, State, and local e-Government services through its website and app. Check UMANG for available services, then follow the service owner’s instructions for eligibility and transactions.'],
                'hi' => ['question' => 'UMANG क्या है और इसकी सेवाएँ कहाँ मिलेंगी?', 'answer' => 'UMANG, MeitY/NeGD का मंच है जहाँ वेबसाइट और ऐप से भाग लेने वाली केंद्र, राज्य और स्थानीय ई-सरकारी सेवाएँ मिलती हैं। उपलब्ध सेवाएँ UMANG पर देखें और पात्रता या लेन-देन के लिए सेवा देने वाले विभाग के निर्देश मानें।'],
            ],
        ];

        $hinglishFaqs = HinglishCatalogueContent::faqs();
        foreach ($faqs as $faq) {
            $hinglish = $hinglishFaqs[$faq['en']['question']] ?? null;
            foreach (['en', 'hi'] as $language) {
                Faq::updateOrCreate(
                    ['category' => $faq['category'], 'language' => $language, 'question' => $faq[$language]['question']],
                    [
                        'answer' => $faq[$language]['answer'],
                        'question_hinglish' => $language === 'en' ? ($hinglish['question'] ?? null) : null,
                        'answer_hinglish' => $language === 'en' ? ($hinglish['answer'] ?? null) : null,
                        'official_source_url' => $faq['url'],
                        'keywords' => $faq['keywords'],
                        'is_active' => true,
                    ]
                );
            }
        }

        Faq::updateOrCreate(
            ['category' => 'about', 'language' => 'en', 'question' => 'Is this an official government website?'],
            [
                'answer' => 'No. SevaSetu is an informational academic project. It does not submit applications, verify identity, or approve benefits, and it does not represent a government department. Use the linked official source for current requirements and decisions.',
                'question_hinglish' => $hinglishFaqs['Is this an official government website?']['question'],
                'answer_hinglish' => $hinglishFaqs['Is this an official government website?']['answer'],
                'official_source_url' => null,
                'keywords' => ['is this official', 'official website', 'government website', 'about this chatbot'],
                'is_active' => true,
            ]
        );
        Faq::updateOrCreate(
            ['category' => 'about', 'language' => 'hi', 'question' => 'क्या यह सरकारी वेबसाइट है?'],
            [
                'answer' => 'नहीं। SevaSetu एक सूचनात्मक शैक्षणिक परियोजना है। यह आवेदन जमा, पहचान सत्यापित या लाभ स्वीकृत नहीं करती और किसी सरकारी विभाग का प्रतिनिधित्व नहीं करती। वर्तमान आवश्यकताओं और निर्णयों के लिए जुड़े आधिकारिक स्रोत का उपयोग करें।',
                'question_hinglish' => null,
                'answer_hinglish' => null,
                'official_source_url' => null,
                'keywords' => ['क्या यह आधिकारिक है', 'सरकारी वेबसाइट', 'इस चैटबॉट के बारे में'],
                'is_active' => true,
            ]
        );
    }
}
