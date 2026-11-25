<?php
/**
 * Central tool registry — single source of truth for the home page, footer,
 * sitemap, search, SEO blocks and the database seed.
 * To add a new tool: add an entry here, create tools/<slug>.php and assets/js/tools/<slug>.js.
 */
declare(strict_types=1);

function tool_categories(): array
{
    return [
        'image' => ['name' => 'Image Tools',          'icon' => '🖼️', 'sort' => 1],
        'calc'  => ['name' => 'Calculators',          'icon' => '🧮', 'sort' => 2],
        'text'  => ['name' => 'Text Tools',           'icon' => '✏️', 'sort' => 3],
        'dev'   => ['name' => 'Developer & Security', 'icon' => '🛠️', 'sort' => 4],
    ];
}

function tools_all(): array
{
    static $tools = null;
    if ($tools !== null) {
        return $tools;
    }

    $tools = [
        /* ---------------- IMAGE ---------------- */
        'image-compressor' => [
            'name' => 'Image Compressor', 'icon' => '🗜️', 'cat' => 'image', 'popular' => true,
            'short' => 'Compress JPG and WebP images instantly in your browser — no uploads.',
            'keywords' => 'compress image reduce size jpg webp png optimize photo',
            'intro' => 'Our Image Compressor reduces the file size of JPG and WebP images directly in your browser. Nothing is uploaded to a server — everything runs locally with the HTML5 Canvas API, so your photos stay private. Smaller images load faster, save mobile data and improve website speed and SEO.',
            'steps' => ['Select an image from your device.', 'Pick a quality level with the slider (lower = smaller file).', 'Click “Compress Image” and download the result.'],
            'faq' => [
                ['Is my image uploaded to a server?', 'No. Compression happens entirely inside your browser using JavaScript and the Canvas API.'],
                ['Which formats work best?', 'JPG and WebP respond to the quality slider. PNG is lossless, so browsers ignore the quality value — use the Image Converter to turn a PNG into JPG/WebP first for real savings.'],
                ['Is there a file size limit?', 'Only your device memory. Most images up to 50 MB work fine.'],
            ],
        ],
        'image-converter' => [
            'name' => 'Image Converter', 'icon' => '🔄', 'cat' => 'image',
            'short' => 'Convert images between JPG, PNG and WebP formats client-side.',
            'keywords' => 'convert image png to jpg jpg to png webp format',
            'intro' => 'Convert any image between JPG, PNG and WebP in seconds. Whether you need a smaller JPG, a transparent PNG or a modern WebP for your website, this converter does it locally in your browser with zero uploads.',
            'steps' => ['Choose an image file.', 'Select the target format.', 'Click “Convert” and download the new file.'],
            'faq' => [
                ['Does conversion reduce quality?', 'JPG and WebP are lossy; we use 92% quality by default. PNG output is lossless.'],
                ['What happens to transparency?', 'Transparent areas become white when converting to JPG, because JPG has no alpha channel.'],
                ['Can I convert many images at once?', 'Currently one at a time. Batch support is planned.'],
            ],
        ],
        'image-resizer' => [
            'name' => 'Image Resizer', 'icon' => '📐', 'cat' => 'image', 'new' => true,
            'short' => 'Resize images by pixels or percentage while keeping the aspect ratio.',
            'keywords' => 'resize image scale dimensions width height pixels thumbnail',
            'intro' => 'Resize photos to exact pixel dimensions or by percentage. Perfect for social media posts, profile pictures, website thumbnails and email attachments. The aspect ratio lock keeps your image from looking stretched, and everything is processed privately in your browser.',
            'steps' => ['Select an image.', 'Enter a new width or height (or choose a percentage).', 'Click “Resize” and download the result.'],
            'faq' => [
                ['Will resizing reduce quality?', 'Scaling down keeps images sharp. Scaling up cannot add detail, so very large enlargements may look soft.'],
                ['Is the aspect ratio preserved?', 'Yes, when “Lock aspect ratio” is ticked (default).'],
            ],
        ],

        /* ---------------- CALCULATORS ---------------- */
        'cgpa-calculator' => [
            'name' => 'CGPA Calculator', 'icon' => '🎓', 'cat' => 'calc', 'popular' => true,
            'short' => 'Calculate semester GPA and cumulative CGPA with credit hours.',
            'keywords' => 'cgpa gpa calculator grade point university marks semester',
            'intro' => 'CGPA (Cumulative Grade Point Average) shows your overall academic performance. This calculator uses the weighted-average formula: multiply each course’s credit hours by its grade point, add them up and divide by total credit hours (4.0 scale).',
            'steps' => ['Enter credit hours for every course.', 'Enter the grade point (0.00–4.00) for each course.', 'Use “+ Add Row” for more courses.', 'Click “Calculate CGPA”.'],
            'faq' => [
                ['Which scale does it use?', 'The standard 4.0 scale used by most universities.'],
                ['Can I include several semesters?', 'Yes — add all courses from all semesters to get the cumulative CGPA.'],
            ],
        ],
        'age-calculator' => [
            'name' => 'Age Calculator', 'icon' => '🎂', 'cat' => 'calc',
            'short' => 'Find your exact age in years, months, days, hours and minutes.',
            'keywords' => 'age calculator date of birth birthday how old',
            'intro' => 'Enter your date of birth and instantly see your exact age in years, months and days — plus total days, weeks and hours lived and the number of days until your next birthday. Leap years and different month lengths are handled correctly.',
            'steps' => ['Enter your date of birth.', 'Optionally choose a different “calculate until” date.', 'Click “Calculate Age”.'],
            'faq' => [
                ['Does it handle leap years?', 'Yes, including people born on 29 February.'],
                ['Can I use it for forms or visas?', 'It is great for a quick check, but always confirm official age with your documents.'],
            ],
        ],
        'emi-calculator' => [
            'name' => 'EMI Calculator', 'icon' => '💰', 'cat' => 'calc', 'popular' => true,
            'short' => 'Compute loan EMI, total interest and a full payment schedule.',
            'keywords' => 'emi loan calculator installment interest amortization bank',
            'intro' => 'EMI (Equated Monthly Instalment) = [P × r × (1+r)^n] / [(1+r)^n − 1], where P is the loan amount, r the monthly interest rate and n the number of months. Choose your currency, see total interest and view a month-by-month amortization schedule.',
            'steps' => ['Enter the loan amount.', 'Enter the annual interest rate.', 'Enter the tenure in months or years.', 'Click “Calculate EMI”.'],
            'faq' => [
                ['What is EMI?', 'A fixed amount a borrower pays the lender every month until the loan is cleared.'],
                ['Does it include taxes or fees?', 'No. Processing fees and taxes depend on your bank.'],
            ],
        ],
        'bmi-calculator' => [
            'name' => 'BMI Calculator', 'icon' => '⚖️', 'cat' => 'calc', 'new' => true,
            'short' => 'Calculate Body Mass Index in metric or imperial units.',
            'keywords' => 'bmi calculator body mass index weight height health',
            'intro' => 'Body Mass Index (BMI) is weight divided by height squared. It is a quick screening number — not a diagnosis. Enter your height and weight in metric (kg/cm) or imperial (lb/ft-in) units to see your BMI and category.',
            'steps' => ['Choose metric or imperial units.', 'Enter your height and weight.', 'Click “Calculate BMI”.'],
            'faq' => [
                ['Is BMI accurate for everyone?', 'No. It does not distinguish muscle from fat and is less reliable for athletes, children and older adults. Talk to a doctor for health advice.'],
                ['What are the BMI categories?', 'Under 18.5 underweight, 18.5–24.9 normal, 25–29.9 overweight, 30+ obese (WHO adult ranges).'],
            ],
        ],
        'percentage-calculator' => [
            'name' => 'Percentage Calculator', 'icon' => '％', 'cat' => 'calc', 'new' => true,
            'short' => 'Quickly find percentages, percentage change and “what % is X of Y”.',
            'keywords' => 'percentage calculator percent increase decrease discount',
            'intro' => 'Solve the three most common percentage problems in one place: X% of a number, what percent one number is of another, and the percentage increase or decrease between two values. Great for discounts, marks, tax and business reports.',
            'steps' => ['Pick the calculator block you need.', 'Enter the two numbers.', 'The result updates instantly.'],
            'faq' => [
                ['How do I calculate a discount?', 'Use “What is X% of Y?” with the discount percentage and the original price, then subtract it from the price.'],
                ['How is percentage change calculated?', '(New − Old) ÷ Old × 100.'],
            ],
        ],

        /* ---------------- TEXT ---------------- */
        'word-counter' => [
            'name' => 'Word Counter', 'icon' => '📝', 'cat' => 'text', 'popular' => true,
            'short' => 'Count words, characters, sentences and estimate reading time live.',
            'keywords' => 'word counter character count sentences reading time essay',
            'intro' => 'Paste or type your text and see words, characters (with and without spaces), sentences, paragraphs and estimated reading time update live. Useful for essays, blog posts, SEO meta descriptions and social media limits.',
            'steps' => ['Paste or type your text.', 'Read the live statistics.', 'Use “Clear” to start over.'],
            'faq' => [
                ['Does it count spaces?', 'Both counts are shown: with and without spaces.'],
                ['How is reading time estimated?', 'At an average of 200 words per minute.'],
            ],
        ],
        'case-converter' => [
            'name' => 'Case Converter', 'icon' => '🔠', 'cat' => 'text',
            'short' => 'Switch text to UPPERCASE, lowercase, Title Case, Sentence case and more.',
            'keywords' => 'case converter uppercase lowercase title case sentence camel snake kebab',
            'intro' => 'Change the letter case of any text in one click: UPPERCASE, lowercase, Title Case, Sentence case, camelCase, snake_case and kebab-case. Line breaks are preserved and nothing leaves your browser.',
            'steps' => ['Paste your text.', 'Click the case style you want.', 'Copy the result.'],
            'faq' => [
                ['Are line breaks kept?', 'Yes.'],
                ['Is my text stored?', 'No — everything is processed locally.'],
            ],
        ],
        'fancy-fonts' => [
            'name' => 'Fancy Fonts', 'icon' => '✨', 'cat' => 'text',
            'short' => 'Generate stylish Unicode text for Instagram, WhatsApp and gaming names.',
            'keywords' => 'fancy fonts stylish text generator instagram bio unicode cool',
            'intro' => 'Turn plain text into bold, italic, script, double-struck, monospace, circled and more Unicode styles that you can paste into social media bios, usernames and chats. Click any style to copy it.',
            'steps' => ['Type your text.', 'Browse the generated styles.', 'Click “Copy” on the one you like.'],
            'faq' => [
                ['Will the styles work everywhere?', 'Most modern apps support Unicode, but some older systems may show boxes instead of characters.'],
                ['Are these real fonts?', 'They are Unicode symbols that look like fonts, so they work without installing anything.'],
            ],
        ],
        'lorem-ipsum-generator' => [
            'name' => 'Lorem Ipsum Generator', 'icon' => '📄', 'cat' => 'text', 'new' => true,
            'short' => 'Create placeholder paragraphs, sentences or words for designs.',
            'keywords' => 'lorem ipsum generator placeholder dummy text',
            'intro' => 'Generate dummy text for mock-ups, templates and layouts. Choose paragraphs, sentences or words, and optionally start with the classic “Lorem ipsum dolor sit amet”.',
            'steps' => ['Choose the type and amount.', 'Click “Generate”.', 'Copy the text into your design.'],
            'faq' => [['Is the text random?', 'Yes — each click assembles a new sample from classic Latin-style words.']],
        ],

        /* ---------------- DEVELOPER & SECURITY ---------------- */
        'password-generator' => [
            'name' => 'Password Generator', 'icon' => '🔐', 'cat' => 'dev', 'new' => true, 'popular' => true,
            'short' => 'Generate strong random passwords with a strength meter.',
            'keywords' => 'password generator strong random secure passphrase',
            'intro' => 'Create strong, random passwords using your browser’s cryptographically secure random generator (crypto.getRandomValues). Choose length and character types and see an instant strength rating. Passwords are never sent anywhere.',
            'steps' => ['Pick a length (12+ recommended).', 'Choose character types.', 'Click “Generate” and copy the password.'],
            'faq' => [
                ['Is it really random?', 'Yes — it uses the Web Crypto API, the same source browsers use for security features.'],
                ['Do you store my passwords?', 'Never. Generation happens only on your device.'],
            ],
        ],
        'json-formatter' => [
            'name' => 'JSON Formatter', 'icon' => '🧾', 'cat' => 'dev', 'new' => true,
            'short' => 'Format, validate and minify JSON with clear error messages.',
            'keywords' => 'json formatter validator beautify minify pretty print',
            'intro' => 'Paste JSON to beautify it with proper indentation, minify it for production, or validate it and see where an error is. Ideal for API responses, config files and debugging.',
            'steps' => ['Paste your JSON.', 'Click “Format” or “Minify”.', 'Fix any error shown and copy the result.'],
            'faq' => [['Is my JSON uploaded?', 'No. Parsing is done locally with JSON.parse.']],
        ],
        'base64-tool' => [
            'name' => 'Base64 Encoder / Decoder', 'icon' => '🔣', 'cat' => 'dev', 'new' => true,
            'short' => 'Encode text to Base64 or decode Base64 back to text (UTF-8 safe).',
            'keywords' => 'base64 encode decode converter text',
            'intro' => 'Encode plain text to Base64 or decode Base64 strings back to readable text. Unicode (including Urdu, Arabic and emoji) is handled correctly via UTF-8.',
            'steps' => ['Paste text or a Base64 string.', 'Click “Encode” or “Decode”.', 'Copy the output.'],
            'faq' => [['Is Base64 encryption?', 'No. It is only an encoding — anyone can decode it, so never use it to protect secrets.']],
        ],
        'unit-converter' => [
            'name' => 'Unit Converter', 'icon' => '📏', 'cat' => 'dev', 'new' => true,
            'short' => 'Convert length, weight, temperature, area, volume and data units.',
            'keywords' => 'unit converter length weight temperature area volume data km miles kg lb',
            'intro' => 'Convert between common units: length, weight, temperature, area, volume and digital storage. Results update instantly as you type, with swap support.',
            'steps' => ['Choose a category.', 'Select “from” and “to” units.', 'Type a value — the result updates instantly.'],
            'faq' => [['How accurate is it?', 'Conversions use standard factors and display up to 8 significant digits.']],
        ],
    ];

    foreach ($tools as $slug => &$t) {
        $t['slug'] = $slug;
        $t['popular'] = $t['popular'] ?? false;
        $t['new'] = $t['new'] ?? false;
    }
    unset($t);
    return $tools;
}

function tool_get(string $slug): ?array
{
    return tools_all()[$slug] ?? null;
}

function tool_url(string $slug): string
{
    return url('tools/' . $slug . '.php');
}

function tools_by_category(): array
{
    $out = [];
    foreach (tools_all() as $t) {
        $out[$t['cat']][] = $t;
    }
    return $out;
}

function tools_related(string $slug, int $limit = 4): array
{
    $current = tool_get($slug);
    if (!$current) {
        return [];
    }
    $same = $others = [];
    foreach (tools_all() as $s => $t) {
        if ($s === $slug) {
            continue;
        }
        if ($t['cat'] === $current['cat']) {
            $same[] = $t;
        } else {
            $others[] = $t;
        }
    }
    return array_slice(array_merge($same, $others), 0, $limit);
}

/** Tool data for client-side search (no heavy text). */
function tools_search_index(): array
{
    $cats = tool_categories();
    $out = [];
    foreach (tools_all() as $t) {
        $out[] = [
            'slug' => $t['slug'], 'name' => $t['name'], 'icon' => $t['icon'],
            'cat'  => $cats[$t['cat']]['name'], 'short' => $t['short'],
            'kw'   => $t['keywords'], 'url' => tool_url($t['slug']),
        ];
    }
    return $out;
}
