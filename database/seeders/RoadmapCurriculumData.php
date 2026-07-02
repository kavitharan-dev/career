<?php

namespace Database\Seeders;

/**
 * Real, career-specific learning paths (W3Schools / Coursera style).
 * Each task includes concrete subtasks — not generic placeholders.
 */
class RoadmapCurriculumData
{
    public static function programming(): array
    {
        return self::javaFromScratch();
    }

    /** Java programmer path from absolute zero. */
    public static function javaFromScratch(): array
    {
        return [
            [
                'title' => 'Java Setup & First Programs',
                'level' => 'beginner',
                'sort_order' => 1,
                'description' => 'Install tools and write your first Java applications (console).',
                'tasks' => [
                    self::task(1, 'Install JDK 21 and IntelliJ IDEA', 'Download Oracle JDK or Temurin, install IntelliJ Community, create a Maven project.', [
                        'Download and install JDK 21 (verify with java -version in terminal)',
                        'Install IntelliJ IDEA Community and create project "HelloJava"',
                        'Run public static void main printing your name',
                    ]),
                    self::task(2, 'Variables, types, and operators', 'Learn int, double, boolean, String, and arithmetic/logical operators.', [
                        'Study W3Schools Java Variables + Data Types sections',
                        'Write a program that converts Celsius to Fahrenheit',
                        'Add input with Scanner and validate a positive number',
                    ]),
                    self::task(3, 'Control flow: if, switch, loops', 'Solve problems using conditionals and for/while loops.', [
                        'Complete 5 exercises: even/odd, grade calculator, multiplication table',
                        'Implement switch for a simple menu (1=add, 2=subtract, 3=exit)',
                        'Use nested loops to print patterns (triangle, pyramid)',
                    ]),
                    self::task(4, 'Methods and debugging', 'Create reusable methods and use the debugger.', [
                        'Refactor calculator logic into methods with parameters and return values',
                        'Practice step-through debugging with breakpoints in IntelliJ',
                        'Write unit-style checks (assert expected output in main)',
                    ]),
                ],
            ],
            [
                'title' => 'Object-Oriented Java',
                'level' => 'intermediate',
                'sort_order' => 2,
                'description' => 'Classes, objects, inheritance, and core APIs.',
                'tasks' => [
                    self::task(1, 'Classes, objects, encapsulation', 'Model real-world entities with fields, constructors, getters/setters.', [
                        'Create Student and Course classes with private fields',
                        'Add constructors, toString(), and equals/hashCode basics',
                        'Build a small enrollment demo in main',
                    ]),
                    self::task(2, 'Inheritance, interfaces, polymorphism', 'Use extends/implements for extensible design.', [
                        'Study W3Schools Java OOP: inheritance + interfaces',
                        'Create abstract Shape with Circle and Rectangle subclasses',
                        'Use List<Shape> and demonstrate polymorphism',
                    ]),
                    self::task(3, 'Collections Framework', 'ArrayList, HashMap, HashSet for real data structures.', [
                        'Store students in ArrayList; lookup by id with HashMap',
                        'Sort a list with Comparator (by name, by grade)',
                        'Handle duplicates with Set and explain when to use each collection',
                    ]),
                    self::task(4, 'Exceptions & file I/O', 'try/catch, custom exceptions, read/write text files.', [
                        'Read a CSV of students and parse into objects (handle bad lines)',
                        'Write results to output.txt with try-with-resources',
                        'Create a custom InvalidGradeException and use it',
                    ]),
                ],
            ],
            [
                'title' => 'Java for Real Applications',
                'level' => 'advanced',
                'sort_order' => 3,
                'description' => 'Database access, Spring Boot, REST APIs, and portfolio project.',
                'tasks' => [
                    self::task(1, 'JDBC & MySQL basics', 'Connect Java to a relational database.', [
                        'Install MySQL (or use SQLite/H2 for practice) and create tables',
                        'Write JDBC code: INSERT, SELECT, UPDATE with PreparedStatement',
                        'Build a CLI that lists and adds records safely (no SQL injection)',
                    ]),
                    self::task(2, 'Spring Boot introduction', 'Create a REST API with Spring Initializr.', [
                        'Generate project at start.spring.io (Web, JPA, H2 or MySQL)',
                        'Create REST endpoints GET/POST for one resource (e.g. books)',
                        'Test with Postman or browser; understand @RestController and @RequestMapping',
                    ]),
                    self::task(3, 'Build a mini REST project', 'Student library API or task manager with persistence.', [
                        'Add validation (@Valid) and proper HTTP status codes',
                        'Connect JPA entities and repository layer',
                        'Document API endpoints in README',
                    ]),
                    self::task(4, 'Portfolio capstone: deploy your Java API', 'Package and share your work on GitHub.', [
                        'Push project to GitHub with clear README and setup steps',
                        'Optional: deploy JAR to Render/Railway free tier',
                        'Write a short reflection: what you learned from zero to API',
                    ]),
                ],
            ],
        ];
    }

    public static function aiEngineering(): array
    {
        return [
            [
                'title' => 'Python & Math for AI',
                'level' => 'beginner',
                'sort_order' => 1,
                'description' => 'Foundation skills every AI engineer needs before models.',
                'tasks' => [
                    self::task(1, 'Python for data & automation', 'NumPy-ready Python (functions, lists, dicts, venv).', [
                        'Install Python 3.11+, create venv, pip install numpy pandas',
                        'Complete W3Schools Python basics + NumPy array exercises (10 examples)',
                        'Script: load a CSV with pandas and print shape, dtypes, head()',
                    ]),
                    self::task(2, 'Linear algebra & statistics essentials', 'Vectors, matrices, mean, variance, probability intuition.', [
                        'Watch/cover: vectors, dot product, matrix multiply (3Blue1Brown or Khan intro)',
                        'Compute mean, std, correlation on a dataset column in pandas',
                        'Quiz yourself: when to normalize features before ML',
                    ]),
                    self::task(3, 'Data exploration workflow', 'Clean, visualize, and ask questions of data.', [
                        'Use matplotlib/seaborn: histogram, scatter, heatmap on sample data',
                        'Handle missing values and outliers on one real dataset (Kaggle titanic or iris)',
                        'Write 5 hypotheses you could test with ML',
                    ]),
                    self::task(4, 'First ML model with scikit-learn', 'Train and evaluate a classifier end-to-end.', [
                        'Follow sklearn docs: train/test split, fit LogisticRegression or RandomForest',
                        'Report accuracy, confusion matrix, and one improvement idea',
                        'Save model with joblib and load it in a second script',
                    ]),
                ],
            ],
            [
                'title' => 'Machine Learning Engineering',
                'level' => 'intermediate',
                'sort_order' => 2,
                'description' => 'Pipelines, tuning, and production-minded ML practice.',
                'tasks' => [
                    self::task(1, 'Feature engineering & pipelines', 'Build reproducible sklearn Pipelines.', [
                        'Combine ColumnTransformer + Pipeline (scale numeric, encode categorical)',
                        'Cross-validate with cross_val_score and compare 2 models',
                        'Document features and target definition in a notebook',
                    ]),
                    self::task(2, 'Model selection & hyperparameters', 'GridSearch, metrics beyond accuracy.', [
                        'Use GridSearchCV on one model; record best params',
                        'For classification: precision, recall, F1; explain tradeoffs',
                        'Plot learning curve or validation curve',
                    ]),
                    self::task(3, 'Intro to neural networks (PyTorch)', 'Tensors, autograd, simple network.', [
                        'Install PyTorch; complete official 60-min blitz intro (tensors)',
                        'Train a small MLP on MNIST or fashion-MNIST',
                        'Track loss per epoch; save best weights',
                    ]),
                    self::task(4, 'NLP basics: text to vectors', 'Tokenization, embeddings, simple text classifier.', [
                        'Preprocess text: lowercase, tokenize, remove stopwords',
                        'Train TF-IDF + linear model on sentiment or category labels',
                        'Try one pretrained embedding example (sentence-transformers optional)',
                    ]),
                ],
            ],
            [
                'title' => 'Deep Learning & AI Products',
                'level' => 'advanced',
                'sort_order' => 3,
                'description' => 'Transformers, fine-tuning concepts, and deploying AI in apps.',
                'tasks' => [
                    self::task(1, 'CNN or transfer learning project', 'Computer vision with pretrained models.', [
                        'Use torchvision pretrained ResNet; fine-tune last layer on small image set',
                        'Augment data (flips, crops) and compare val accuracy',
                        'Write inference script: image path → predicted class',
                    ]),
                    self::task(2, 'Transformers & LLM APIs', 'How modern AI apps use large models.', [
                        'Read Hugging Face Transformers pipeline docs; run summarization or QA demo',
                        'Call OpenAI-compatible or free API with prompts (system + user messages)',
                        'Compare 3 prompts for same task; note cost/latency/safety',
                    ]),
                    self::task(3, 'RAG pattern (retrieval + generation)', 'Build a simple knowledge assistant.', [
                        'Chunk documents; embed with sentence-transformers or API embeddings',
                        'Retrieve top-k chunks for a user question',
                        'Generate answer citing retrieved text (basic RAG notebook)',
                    ]),
                    self::task(4, 'Deploy an AI feature (API)', 'FastAPI + model serving for real users.', [
                        'Wrap your best model in FastAPI endpoint (/predict)',
                        'Add input validation and error handling',
                        'Deploy locally with uvicorn; document curl examples in README',
                    ]),
                    self::task(5, 'AI Engineering capstone', 'End-to-end portfolio project.', [
                        'Choose problem: classifier, chatbot, or recommendation demo',
                        'GitHub repo: data, training notebook, API, short demo video or screenshots',
                        'Write ethics note: bias, privacy, and limitations of your model',
                    ]),
                ],
            ],
        ];
    }

    public static function dataScience(): array
    {
        return [
            [
                'title' => 'Data Foundations with Python',
                'level' => 'beginner',
                'sort_order' => 1,
                'description' => 'Python, pandas, and exploratory analysis.',
                'tasks' => [
                    self::task(1, 'Python & Jupyter setup', 'Environment for data work.', [
                        'Install Anaconda or venv; launch Jupyter Lab',
                        'Practice lists, dicts, list comprehensions (20 min exercises)',
                        'Import pandas, read CSV, describe() and info()',
                    ]),
                    self::task(2, 'Descriptive statistics', 'Summarize and compare distributions.', [
                        'Compute mean, median, mode, std on numeric columns',
                        'Group by category and aggregate (groupby)',
                        'Interpret skew and outliers on one column',
                    ]),
                    self::task(3, 'Visualization storytelling', 'Charts that answer business questions.', [
                        'Create bar, line, histogram, boxplot for one dataset',
                        'Add titles, labels, legends; one chart per insight',
                        'Write 3 bullet conclusions for a mock stakeholder',
                    ]),
                ],
            ],
            [
                'title' => 'Analytics & SQL',
                'level' => 'intermediate',
                'sort_order' => 2,
                'description' => 'Query databases and build analytical datasets.',
                'tasks' => [
                    self::task(1, 'SQL fundamentals', 'SELECT, JOIN, GROUP BY, HAVING.', [
                        'Complete W3Schools SQL tutorial sections 1–8',
                        'Write 10 queries on sample DB (employees, orders, or SQLite chinook)',
                        'Explain INNER vs LEFT JOIN with examples',
                    ]),
                    self::task(2, 'Data cleaning pipeline', 'Production-quality tables in pandas.', [
                        'Standardize dates, categories, and missing value strategy',
                        'Detect duplicates and document removal rules',
                        'Export clean parquet/CSV for modeling',
                    ]),
                    self::task(3, 'A/B test analysis', 'Compare groups with statistical thinking.', [
                        'Simulate or use sample A/B data; compute conversion rates',
                        'Optional: chi-square or t-test with scipy',
                        'Recommend action based on results (not only p-values)',
                    ]),
                ],
            ],
            [
                'title' => 'Modeling & Communication',
                'level' => 'advanced',
                'sort_order' => 3,
                'description' => 'ML models and present insights professionally.',
                'tasks' => [
                    self::task(1, 'Regression & classification project', 'Full sklearn workflow.', [
                        'Define problem, baseline model, and metric',
                        'Tune one model; plot feature importance or coefficients',
                        'Explain errors on worst predictions',
                    ]),
                    self::task(2, 'Dashboard or report', 'Share results clearly.', [
                        'Build Streamlit page OR PDF report with key charts',
                        'Executive summary: problem, method, result, next step',
                        'Peer review checklist: reproducible, labeled axes, sources',
                    ]),
                ],
            ],
        ];
    }

    public static function webDevelopment(): array
    {
        return [
            [
                'title' => 'HTML, CSS & Responsive UI',
                'level' => 'beginner',
                'sort_order' => 1,
                'description' => 'Build static pages like W3Schools front-end track.',
                'tasks' => [
                    self::task(1, 'Semantic HTML5 page', 'Structure content correctly.', [
                        'Build personal portfolio page: header, nav, main, sections, footer',
                        'Validate HTML at validator.w3.org',
                        'Add meta tags, alt text, accessible headings',
                    ]),
                    self::task(2, 'CSS layout: Flexbox & Grid', 'Modern responsive layouts.', [
                        'Recreate a simple landing page layout with flexbox',
                        'Add CSS Grid for a card gallery (3 columns desktop, 1 mobile)',
                        'Use media queries for breakpoints',
                    ]),
                    self::task(3, 'Tailwind or CSS variables', 'Utility-first styling (matches this app stack).', [
                        'Rebuild one component with Tailwind classes',
                        'Practice spacing, colors, typography scale',
                        'Dark mode toggle optional challenge',
                    ]),
                ],
            ],
            [
                'title' => 'JavaScript & Browser APIs',
                'level' => 'intermediate',
                'sort_order' => 2,
                'description' => 'Interactive frontends and async data.',
                'tasks' => [
                    self::task(1, 'JavaScript fundamentals', 'DOM, events, ES6+.', [
                        'W3Schools JS: variables, functions, arrays, objects',
                        'Todo list app: add, delete, mark complete in DOM',
                        'Use addEventListener and prevent default on forms',
                    ]),
                    self::task(2, 'Fetch API & JSON', 'Load remote data.', [
                        'Fetch from public API (JSONPlaceholder or weather API)',
                        'Render list with loading and error states',
                        'Format dates and handle empty results',
                    ]),
                    self::task(3, 'Forms & validation', 'Client-side UX before server.', [
                        'Validate email, required fields, min length',
                        'Show inline error messages',
                        'Submit via fetch POST to mock endpoint',
                    ]),
                ],
            ],
            [
                'title' => 'Full-Stack Web Apps',
                'level' => 'advanced',
                'sort_order' => 3,
                'description' => 'Laravel backend + deployable project.',
                'tasks' => [
                    self::task(1, 'Laravel routing, Blade, Eloquent', 'Server-rendered app basics.', [
                        'Create resource CRUD for one model (notes or books)',
                        'Use migrations, factories, and validation',
                        'Auth: register/login (use Laravel Breeze docs)',
                    ]),
                    self::task(2, 'REST API + front-end consumer', 'Separate API and UI.', [
                        'Build JSON API routes with Sanctum or session',
                        'Consume API from JS or Blade AJAX',
                        'Return proper status codes and JSON errors',
                    ]),
                    self::task(3, 'Deploy full-stack project', 'Live URL for portfolio.', [
                        'Environment variables and production config checklist',
                        'Deploy to shared hosting, Render, or Laravel Forge trial',
                        'Post-deploy smoke test all main flows',
                    ]),
                ],
            ],
        ];
    }

    public static function design(): array
    {
        return [
            [
                'title' => 'Visual Design Foundations',
                'level' => 'beginner',
                'sort_order' => 1,
                'description' => 'Color, type, spacing — basis of UI design.',
                'tasks' => [
                    self::task(1, 'Color systems & contrast', 'Accessible palettes.', [
                        'Study color wheel; build primary/secondary/neutral scale',
                        'Check contrast ratios (WebAIM contrast checker)',
                        'Apply palette to 3 button states (default, hover, disabled)',
                    ]),
                    self::task(2, 'Typography hierarchy', 'Readable interfaces.', [
                        'Pick 2 Google Fonts (heading + body); define scale (h1–h6, body)',
                        'Design type specimen page in Figma',
                        'Line height and max-width for paragraphs',
                    ]),
                ],
            ],
            [
                'title' => 'UX & Figma Prototyping',
                'level' => 'intermediate',
                'sort_order' => 2,
                'description' => 'User flows and interactive prototypes.',
                'tasks' => [
                    self::task(1, 'User research & personas', 'Design for real users.', [
                        'Interview 2 people or use personas template',
                        'List pain points and goals for one app idea',
                        'Map user journey (5–7 steps)',
                    ]),
                    self::task(2, 'Wireframes to high-fidelity', 'Figma auto-layout.', [
                        'Low-fi 5 screens → hi-fi with components',
                        'Create button, input, card components with variants',
                        'Prototype click-through main flow',
                    ]),
                ],
            ],
            [
                'title' => 'Design Systems & Portfolio',
                'level' => 'advanced',
                'sort_order' => 3,
                'description' => 'Professional deliverables.',
                'tasks' => [
                    self::task(1, 'Usability testing', 'Validate designs.', [
                        'Run 3-task test with 2 users; record issues',
                        'Prioritize fixes; update Figma',
                        'Before/after screenshot comparison',
                    ]),
                    self::task(2, 'Case study for portfolio', 'Tell the design story.', [
                        'Problem, process, solution, results sections',
                        'Include wireframes, final UI, and learnings',
                        'Publish on Behance, Dribbble, or personal site',
                    ]),
                ],
            ],
        ];
    }

    public static function cybersecurity(): array
    {
        return [
            [
                'title' => 'Security Fundamentals',
                'level' => 'beginner',
                'sort_order' => 1,
                'description' => 'CIA triad, threats, and safe computing.',
                'tasks' => [
                    self::task(1, 'Security concepts & ethics', 'Legal and responsible hacking.', [
                        'Study CIA triad, authentication vs authorization',
                        'Read ethical hacking scope (only authorized systems)',
                        'Write personal ethics statement for security work',
                    ]),
                    self::task(2, 'Networking basics for security', 'TCP/IP, ports, DNS, HTTP.', [
                        'W3Schools or Cisco intro: IP, subnet, common ports',
                        'Use ping, traceroute, nslookup on your network',
                        'Draw diagram: browser → DNS → server',
                    ]),
                    self::task(3, 'Linux & command line', 'Essential for security tools.', [
                        'Install Ubuntu VM or WSL',
                        'Practice 20 commands: ls, chmod, grep, ssh, curl',
                        'Create users and file permissions lab',
                    ]),
                ],
            ],
            [
                'title' => 'Offensive & Defensive Basics',
                'level' => 'intermediate',
                'sort_order' => 2,
                'description' => 'Vulnerabilities, scanning, and hardening.',
                'tasks' => [
                    self::task(1, 'OWASP Top 10 overview', 'Web app risks.', [
                        'Read OWASP Top 10 summary; explain XSS and SQLi',
                        'Try DVWA or WebGoat in isolated lab VM only',
                        'Document one vulnerability and mitigation',
                    ]),
                    self::task(2, 'Recon & scanning (lab only)', 'nmap, vulnerability awareness.', [
                        'Scan your own lab VM with nmap (-sV on localhost)',
                        'Interpret open ports; no scanning third-party networks',
                        'Write report template for scan results',
                    ]),
                    self::task(3, 'Cryptography basics', 'Hashing, TLS, passwords.', [
                        'Hash passwords with bcrypt concept (never store plain text)',
                        'Explain HTTPS handshake at high level',
                        'Use openssl or online tool to inspect cert on a site',
                    ]),
                ],
            ],
            [
                'title' => 'Security Operations',
                'level' => 'advanced',
                'sort_order' => 3,
                'description' => 'SOC mindset, incident response, career certs path.',
                'tasks' => [
                    self::task(1, 'Log analysis intro', 'Detect anomalies.', [
                        'Review sample auth logs; find failed login patterns',
                        'Define 3 detection rules (conceptual SIEM)',
                        'Timeline one mock incident',
                    ]),
                    self::task(2, 'Hardening checklist', 'Secure a server.', [
                        'Firewall, SSH keys, disable root login, updates',
                        'Apply checklist to lab VM',
                        'Before/after nmap comparison (your VM only)',
                    ]),
                    self::task(3, 'Certification & career plan', 'Security+ / CEH path.', [
                        'Map Security+ domains to your study plan',
                        'Build home lab diagram in portfolio',
                        'Write target job role (SOC analyst, pentester, GRC)',
                    ]),
                ],
            ],
        ];
    }

    public static function mobileDevelopment(): array
    {
        return [
            [
                'title' => 'Mobile Dev Foundations',
                'level' => 'beginner',
                'sort_order' => 1,
                'description' => 'Choose Android (Kotlin) or cross-platform (Flutter).',
                'tasks' => [
                    self::task(1, 'Platform choice & setup', 'Android Studio or Flutter SDK.', [
                        'Install Android Studio + emulator OR Flutter SDK + doctor',
                        'Run default Hello app on emulator/device',
                        'Understand activity/screen vs widget lifecycle basics',
                    ]),
                    self::task(2, 'UI components & layout', 'Build static screens.', [
                        'Login screen: fields, button, basic validation UI',
                        'List screen: RecyclerView or ListView / Flutter ListView',
                        'Navigate between 2 screens',
                    ]),
                    self::task(3, 'State & user input', 'Make apps interactive.', [
                        'Counter or form app with local state',
                        'Save simple preference (SharedPreferences / shared_preferences)',
                        'Handle rotation or state restore note',
                    ]),
                ],
            ],
            [
                'title' => 'APIs & App Architecture',
                'level' => 'intermediate',
                'sort_order' => 2,
                'description' => 'Connect apps to backend services.',
                'tasks' => [
                    self::task(1, 'HTTP client & JSON', 'Retrofit or http package.', [
                        'Fetch list from public API and display',
                        'Loading spinner and error message UI',
                        'Parse JSON into model classes',
                    ]),
                    self::task(2, 'Local database', 'Room or sqflite.', [
                        'Cache API results offline',
                        'CRUD one entity locally',
                        'Sync strategy: network first, then cache',
                    ]),
                ],
            ],
            [
                'title' => 'Publish & Polish',
                'level' => 'advanced',
                'sort_order' => 3,
                'description' => 'Store-ready app practices.',
                'tasks' => [
                    self::task(1, 'App icon, splash, permissions', 'Store guidelines.', [
                        'Read Google Play / App Store checklist (overview)',
                        'Add adaptive icon and permission rationale strings',
                        'Test on real device',
                    ]),
                    self::task(2, 'Release build & portfolio', 'Signed APK/AAB or TestFlight path.', [
                        'Generate release build (debug signing ok for portfolio)',
                        'Screen recording demo (2 min)',
                        'GitHub README with screenshots',
                    ]),
                ],
            ],
        ];
    }

    public static function digitalMarketing(): array
    {
        return [
            [
                'title' => 'Marketing Foundations',
                'level' => 'beginner',
                'sort_order' => 1,
                'description' => 'Audience, value proposition, channels.',
                'tasks' => [
                    self::task(1, 'Target audience & persona', 'Who you market to.', [
                        'Create 1 detailed buyer persona (demographics, goals, pain)',
                        'Write value proposition canvas for a sample product',
                        'List 3 channels where persona spends time',
                    ]),
                    self::task(2, 'Content marketing basics', 'Blog, social, email outline.', [
                        'Plan 2-week content calendar (4 posts)',
                        'Write one SEO-friendly blog outline (H1, H2, keywords)',
                        'Draft one social post per platform style',
                    ]),
                ],
            ],
            [
                'title' => 'SEO & Paid Ads Intro',
                'level' => 'intermediate',
                'sort_order' => 2,
                'description' => 'Organic and paid acquisition.',
                'tasks' => [
                    self::task(1, 'On-page SEO', 'Keywords and structure.', [
                        'Keyword research with Ubersuggest or Google Keyword Planner',
                        'Optimize one page: title, meta, headings, internal links',
                        'Use Google Search Console (verify site or use demo)',
                    ]),
                    self::task(2, 'Google Ads / Meta Ads fundamentals', 'Campaign structure.', [
                        'Study campaign → ad set → ad hierarchy',
                        'Design one mock ad with headline, description, CTA',
                        'Define budget, audience, and success metric (CPC, CPA)',
                    ]),
                    self::task(3, 'Analytics with GA4', 'Measure what matters.', [
                        'Set up GA4 property (or explore demo account)',
                        'Define 3 events/conversions for a site',
                        'Interpret acquisition vs engagement report',
                    ]),
                ],
            ],
            [
                'title' => 'Growth Strategy',
                'level' => 'advanced',
                'sort_order' => 3,
                'description' => 'Campaign optimization and reporting.',
                'tasks' => [
                    self::task(1, 'A/B test a landing element', 'Data-driven marketing.', [
                        'Hypothesis: headline A vs B improves clicks',
                        'Run simple test (or document planned test)',
                        'Report winner and next iteration',
                    ]),
                    self::task(2, 'Marketing portfolio deck', 'Show campaigns to employers.', [
                        'Case study: objective, tactics, metrics, learnings',
                        'Include screenshots and honest results',
                        'Present 5-slide summary',
                    ]),
                ],
            ],
        ];
    }

    public static function cloudDevops(): array
    {
        return [
            [
                'title' => 'Linux & Cloud Concepts',
                'level' => 'beginner',
                'sort_order' => 1,
                'description' => 'Servers, SSH, and cloud service models.',
                'tasks' => [
                    self::task(1, 'Linux server administration', 'Shell, services, files.', [
                        'Ubuntu VM: users, groups, sudo, systemd service status',
                        'Install nginx and serve static HTML page',
                        'SSH key login; disable password auth in lab',
                    ]),
                    self::task(2, 'Cloud models (IaaS, PaaS, SaaS)', 'AWS/Azure/GCP overview.', [
                        'Compare EC2 vs Lambda vs S3 use cases (or Azure equivalents)',
                        'Create free-tier account; explore console safely',
                        'Estimate monthly cost for a tiny app',
                    ]),
                    self::task(3, 'Git for DevOps', 'Branching and collaboration.', [
                        'git flow: feature branch, PR, merge',
                        'Resolve a simple merge conflict exercise',
                        'Tag a release v1.0.0',
                    ]),
                ],
            ],
            [
                'title' => 'CI/CD & Containers',
                'level' => 'intermediate',
                'sort_order' => 2,
                'description' => 'Docker and automated pipelines.',
                'tasks' => [
                    self::task(1, 'Docker fundamentals', 'Images, containers, Dockerfile.', [
                        'docker run hello-world; build image for a static site or API',
                        'Use docker-compose for app + database',
                        'Push image to Docker Hub',
                    ]),
                    self::task(2, 'GitHub Actions pipeline', 'Build, test, deploy.', [
                        'YAML workflow: on push run tests',
                        'Add lint or phpunit/npm test step',
                        'Artifact or deploy step (document secrets handling)',
                    ]),
                    self::task(3, 'Infrastructure as Code intro', 'Terraform or Bicep awareness.', [
                        'Read Terraform intro: provider, resource, state',
                        'Optional: provision one S3 bucket or storage in lab',
                        'Diagram desired infrastructure',
                    ]),
                ],
            ],
            [
                'title' => 'Production Operations',
                'level' => 'advanced',
                'sort_order' => 3,
                'description' => 'Monitoring, scaling, reliability.',
                'tasks' => [
                    self::task(1, 'Monitoring & logs', 'Observe running systems.', [
                        'Set up health check endpoint /up',
                        'Centralize logs concept (CloudWatch, Loki, or file tail)',
                        'Define 3 alerts (CPU, errors, downtime)',
                    ]),
                    self::task(2, 'Deploy production-like stack', 'Capstone.', [
                        'Deploy containerized app to cloud free tier',
                        'HTTPS with certificate (Let\'s Encrypt or managed)',
                        'Runbook: deploy, rollback, backup steps',
                    ]),
                ],
            ],
        ];
    }

    /**
     * @param  array<int, string>  $subtasks
     * @return array<string, mixed>
     */
    protected static function task(int $day, string $title, string $description, array $subtasks): array
    {
        return [
            'day_number' => $day,
            'title' => $title,
            'description' => $description,
            'subtasks' => $subtasks,
        ];
    }
}
