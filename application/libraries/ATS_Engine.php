<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
require_once FCPATH . 'vendor/autoload.php';

class ATS_Engine {
 
public function processResume($file, $vacancy)
{
 
    $this->logStage('START', [
        'resumePath' => $file,
        'vacancy' => $vacancy['Jid'] ?? null
    ]);
    // $text = strtolower($this->extractText($file));
    $text = strtolower((string)$this->extractText($file));
    $experienceDetails = $this->extractExperienceDetails($text);
    $this->logStage('EXPERIENCE_DETAILS', $experienceDetails);
    $this->logStage('RAW_RESUME_TEXT', substr($text, 0, 2000)); // first 2k chars only
    // ================= EMAIL EXTRACTION =================
    $email = '';
    $name = '';
    $mobileNumbers = '';
    $expyrs = '';
 
    // ================= EXTRACT EMAIL =================
        $email = '';
        if (preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', $text, $matches)) {
            $email = $matches[0];
        }
 
       // ================= EXTRACT NAME =================

        $lines = preg_split('/\r\n|\r|\n/', $text);

       // ================= EXTRACT NAME =================

        $name = '';

        $lines = preg_split('/\r\n|\r|\n/', $text);

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line == '') continue;

            // Skip page number
            if (preg_match('/^\d+$/', $line))
                continue;

            // Skip email
            if (filter_var($line, FILTER_VALIDATE_EMAIL))
                continue;

            // Skip phone
            if (preg_match('/(\+91|91)?\s?[6-9]\d{9}/', $line))
                continue;

            // Skip headings
            if (preg_match('/career|objective|summary|profile|education|experience|skills|projects/i', $line))
                continue;

            // Remove tabs and multiple spaces
            $line = preg_replace('/\s+/', ' ', $line);

            // Capture first name-like text before address
            if (preg_match('/^([A-Za-z][A-Za-z.\s]{2,40})/', $line, $m)) {

                $candidate = trim($m[1]);

                // Ignore obvious address words
                if (!preg_match('/road|street|nagar|district|state|india|tamil nadu|chennai/i', $candidate)) {

                    $name = ucwords(strtolower($candidate));
                    break;
                }
            }
        }
 
        // $lines = explode("\n", $text);
        // $name = null;
 
        // foreach ($lines as $line) {
        //     $line = trim($line);
 
        //     // Skip empty lines
        //     if (strlen($line) < 3) continue;
 
        //     // Skip lines containing email or numbers
        //     if (preg_match('/@|\d/', $line)) continue;
 
        //     // If line looks like a proper name
        //     if (preg_match('/^[A-Za-z ]{3,40}$/', $line)) {
        //         $name = $line;
        //         break;
        //     }
        // }
 
        // ================= EXTRACT MOBILE =================
        $mobileNumbers = [];
        // if (preg_match_all('/(\+?\s?91[\s\-]?)?[6-9]\d{9}/', $text, $matches)) {
        //     foreach ($matches[0] as $num) {
        //         $mobileNumbers[] = preg_replace('/\D/', '', $num); // clean number
        //     }
        // }
        if (preg_match_all('/(\+?\s?91[\s\-]?)?[6-9]\d{9}/', $text, $matches)) {
            $mobileNumbers = array_unique($matches[0]);
            $mobileNumbers = !empty($mobileNumbers)  ? implode(',', $mobileNumbers) : null;
        }
 
        // ================= EXTRACT EXPERIENCE =================
        $candidateExp = 0;

            $patterns = [

            '/overall\s+(\d+(?:\.\d+)?)\+?\s*years?\s+of\s+experience/i',

            '/total\s+(\d+(?:\.\d+)?)\+?\s*years?\s+of\s+experience/i',

            '/(\d+(?:\.\d+)?)\+?\s*years?\s+of\s+experience/i',

            '/experience\s*[:\-]?\s*(\d+(?:\.\d+)?)/i'

            ];

            foreach($patterns as $pattern){

                if(preg_match($pattern,$text,$m)){

                    $candidateExp=(float)$m[1];
                    break;

                }

            }

            if($candidateExp==0 && stripos($text,'intern')!==false){

                $candidateExp=1;

            }
        $expyrs = $candidateExp;
 
    $this->logStage('Email', $email);
    $this->logStage('name', $name);
    $this->logStage('mobileno', $mobileNumbers);
    $this->logStage('expyrs', $expyrs);
 
    //file_put_contents('C:/xampp/htdocs/debug.txt', $text);
 
    $text = preg_replace('/[^a-z0-9 ]/', ' ', $text);
    $text = preg_replace('/\s+/', ' ', $text);
 
    $skillScore = 0;
    $eduScore   = 0;
    $expScore   = 0;
 
    $matchedSkills = [];
    $eduMatch = false;
    $expMatch = false;
 
    // $skills = explode(',', strtolower($vacancy['RequiredSkills']));
    $skills = isset($vacancy['RequiredSkills'])
    ? explode(',', strtolower($vacancy['RequiredSkills']))
    : [];
    $this->logStage('Input skills', $vacancy['RequiredSkills']);
    $this->logStage('Skills', $skills);
    foreach ($skills as $skill) {
 
        $skill = strtolower(trim($skill));
        if (!$skill) continue;
 
        if (preg_match('/\b'.preg_quote($skill,'/').'\b/', $text)) {
            $skillScore += 5;
            $matchedSkills[] = $skill;
            continue;
        }
 
        if ($this->synonymMatch($skill, $text)) {
            $skillScore += 3;
            $matchedSkills[] = $skill.' (synonym)';
            continue;
        }
 
        if ($this->fuzzyMatch($skill, $text)) {
            $skillScore += 2;
            $matchedSkills[] = $skill.' (fuzzy)';
        }
    }
 
    if (strtolower(trim($vacancy['EducationRequired'])) == 'any') {
 
        $eduScore = 10;
        $eduMatch = true;
 
    } else {
 
        $normText = preg_replace('/[^a-z0-9 ]/', ' ', $text);
        $normText = preg_replace('/\s+/', ' ', $normText);
 
        // $edu = explode(',', strtolower($vacancy['EducationRequired']));
        //  $this->logStage('EducationRequired', $vacancy['EducationRequired']);
        //  $this->logStage('edu', $edu);
        // foreach ($edu as $e) {
 
        //     $e = preg_replace('/[^a-z0-9 ]/', ' ', $e);
        //     $e = preg_replace('/\s+/', ' ', $e);
        //     $e = trim($e);
 
        //     if ($e && stripos($normText, $e) !== false) {
        //         $eduScore = 10;
        //         $eduMatch = true;
        //         break;
        //     }
        // }
        $degreeGroups = [
 
    'bachelor' => [
        'btech','b.e','be','bachelor','bsc','bca','bcom','ba','bba','bachelor of technology',
        'bachelor of engineering','bachelor of science','bachelor of commerce','bachelor of arts'
    ],
 
    'master' => [
        'mtech','me','mba','msc','mca','mcom','ma','master','master of technology',
        'master of engineering','master of science','master of business administration'
    ],
 
    'doctorate' => [
        'phd','doctorate','doctor of philosophy'
    ],
 
    'diploma' => [
        'diploma','polytechnic'
    ]
 
];
$eduRequired = strtolower($vacancy['EducationRequired']);
 
foreach ($degreeGroups as $level => $degrees) {
 
    foreach ($degrees as $degree) {
 
        if (stripos($normText, $degree) !== false) {
 
            if (stripos($eduRequired, $level) !== false ||
                stripos($eduRequired, $degree) !== false) {
 
                $eduScore = 10;
                $eduMatch = true;
                break 2;
            }
        }
 
    }
 
}
    }
 
    $candidateExp = 0;
    $cleanText = strtolower($text);
 
    if (preg_match('/(\d+(?:\.\d+)?)\s*(years?|yrs?)/i', $cleanText, $m)) {
        $candidateExp = (float)$m[1];
    }
 
    if ($candidateExp == 0 && strpos($cleanText,'intern') !== false) {
        $candidateExp = 1;
    }
 
    // if ((float)$vacancy['ExpMin'] == 0) {
    //     $expScore = 10;
    //     $expMatch = true;
 
    // } elseif ($candidateExp >= (float)$vacancy['ExpMin']) {
 
    //     $expScore = 10;
    //     $expMatch = true;
    // }
 if ((float)$vacancy['ExpMin'] == 0) {
    $expScore = 10;
    $expMatch = true;

} elseif ($candidateExp >= (float)$vacancy['ExpMin']) {

    $expScore = 10;
    $expMatch = true;
}

// 🔴 ADD THIS BELOW
if ($candidateExp == 0) {
    $expScore = 0;
}
    // $score = $skillScore + $eduScore + $expScore;
    // $status = ($score >= 30) ? 'Shortlisted' : 'Rejected';
    // ================= ADDITIONAL ATS CRITERIA =================
 
// Projects / Achievements (Max 15)
$projectScore = 0;
if (preg_match('/project|projects|developed|built|implemented|achievement|achievements/i', $text)) {
    $projectScore = 5;
}
 
// Certifications / Learning (Max 10)
$certScore = 0;

if(preg_match('/\b(certification|certified|aws certified|oracle certified|microsoft certified|google certified|cisco certified|azure certified)\b/i',$text))
{
    $certScore=10;
}
 
// Resume Quality (Max 10)
$resumeQualityScore = 0;
if (strlen($text) > 500) {
    $resumeQualityScore = 5;
}
 
$domainScore = 0;
$domainName = null;
 
$domainScore = 0;
$domainName = null;
 
$domains = [
 
    'Fintech' => [
        'finance','banking','payment','payments','upi','wallet',
        'trading','investment','insurance','loan'
    ],
 
    'Healthcare' => [
        'hospital','healthcare','medical','clinic','patient',
        'pharmacy','ehr','emr'
    ],
 
    'Ecommerce' => [
        'ecommerce','shopping','cart','checkout','order',
        'product','marketplace'
    ],
 
    'HR' => [
        'recruitment','hiring','talent','payroll',
        'employee','hrms','onboarding'
    ],
 
];
 
foreach ($domains as $domain => $keywords) {
 
    foreach ($keywords as $word) {
 
        if (preg_match('/\b' . preg_quote($word,'/') . '\b/i', $text)) {
 
            $domainName = $domain;
            $domainScore = 5;
 
            break 2;
        }
    }
}

 
// ================= FINAL SCORE =================
 
// $score = $skillScore + $eduScore + $expScore + $projectScore + $certScore + $resumeQualityScore + $domainScore;
 
// $status = ($score >= 60) ? 'Shortlisted' : 'Rejected';
 $score = $skillScore + $eduScore + $expScore + $projectScore + $certScore + $resumeQualityScore + $domainScore;

// 🔴 IF IMPORTANT SKILLS MISSING → LIMIT SCORE
// if ($missingCritical) {
//     $score = min($score, 50);
// }

// 🔴 STATUS LOGIC (3 LEVELS)
if ($score >= 75) {
    $status = 'Shortlisted';
} elseif ($score >= 50) {
    $status = 'Hold';
} else {
    $status = 'Rejected';
}
if (empty($status)) {
    $status = 'Rejected';
}
    return [
 
        'score' => $score,
        'status' => $status,
        'email' => $email,   // Email
        'name' => $name,   // Name
        'mobileNumbers' => $mobileNumbers,   // Mobile No
        'expyrs' => $expyrs,   //years
        'experience_details' => $experienceDetails,
        'domain' => $domainName,
 
        'matched_skills' => implode(', ', $matchedSkills),
        'education_match' => $eduMatch ? 'Yes' : 'No',
        'experience' => $expMatch ? 'Yes' : 'No',
 
     'score_breakdown' => [
    'skills'           => $skillScore,
    'education'        => $eduScore,
    'experience'       => $expScore,
    'projects'         => $projectScore,
    'certifications'   => $certScore,
    'resume_quality'   => $resumeQualityScore,
    'domain_knowledge' => $domainScore
]
    ];
}
 
//////////////// TEXT EXTRACT //////////////////
//////////////// TEXT EXTRACT //////////////////
private function extractText($file)
{
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    try {

        if ($ext == 'txt') {
            return trim(file_get_contents($file));
        }

        elseif ($ext == 'docx') {
            return trim($this->readDocx($file));
        }

        elseif ($ext == 'pdf') {

            if (!file_exists($file)) {
                $this->logStage('PDF_ERROR', 'PDF file not found : ' . $file);
                return '';
            }

            // Parse PDF using Smalot PDF Parser
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($file);
            $text = $pdf->getText();

            if (empty(trim($text))) {
                $this->logStage('PDF_ERROR', 'No text extracted from PDF.');
                return '';
            }

            $this->logStage('PDF_EXTRACT_SUCCESS', substr($text, 0, 1000));

            return trim($text);
        }

        else {
            $this->logStage('ERROR', 'Unsupported file type : ' . $ext);
            return '';
        }

    } catch (\Exception $e) {

        $this->logStage('PDF_EXCEPTION', $e->getMessage());
        return '';
    }
}

private function readDocx($file)
{
    $zip = new ZipArchive;
    $content = '';
 
    if ($zip->open($file) === TRUE) {
 
        $xml = $zip->getFromName('word/document.xml');
        $content = strip_tags($xml);
 
        $zip->close();
    }
 
    return $content;
}
 
private function fuzzyMatch($word, $text, $threshold = 70)
{
    foreach (explode(' ', $text) as $w) {
        similar_text($word, $w, $percent);
        if ($percent >= $threshold) return true;
    }
    return false;
}
 
///////
 
 
private function synonymMatch($skill, $text)
{
    $skill = strtolower(trim($skill));
    $text  = strtolower($text);

    // Direct Match
    if (preg_match('/(?<!\w)' . preg_quote($skill, '/') . '(?!\w)/i', $text)) {
        return true;
    }

    // Skill Dictionary
    $synonyms = [

        // =========================
        // Programming Languages
        // =========================
        'php' => ['php'],
        'javascript' => ['javascript','js','ecmascript'],
        'typescript' => ['typescript','ts'],
        'python' => ['python'],
        'java' => ['java'],
        'c#' => ['c#','csharp','.net'],
        'c++' => ['c++'],
        'golang' => ['golang','go'],
        'ruby' => ['ruby'],
        'swift' => ['swift'],
        'kotlin' => ['kotlin'],

        // =========================
        // Frontend
        // =========================
        'html' => ['html','html5'],
        'css' => ['css','css3'],
        'bootstrap' => ['bootstrap'],
        'tailwind' => ['tailwind','tailwind css'],
        'sass' => ['sass','scss'],
        'jquery' => ['jquery'],
        'react' => ['react','reactjs','react.js'],
        'angular' => ['angular','angularjs'],
        'vue' => ['vue','vuejs'],
        'nextjs' => ['nextjs','next.js'],
        'nuxtjs' => ['nuxtjs','nuxt.js'],
        'redux' => ['redux'],
        'material ui' => ['material ui','mui'],

        // =========================
        // Backend
        // =========================
        'nodejs' => ['node','nodejs','node.js'],
        'express' => ['express','expressjs'],
        'laravel' => ['laravel'],
        'codeigniter' => ['codeigniter','ci'],
        'django' => ['django'],
        'flask' => ['flask'],
        'spring boot' => ['spring boot','springboot'],
        'dotnet' => ['.net','asp.net','dotnet'],
        'rest api' => ['rest','rest api','api'],
        'graphql' => ['graphql'],

        // =========================
        // Database
        // =========================
        'mysql' => ['mysql'],
        'postgresql' => ['postgres','postgresql'],
        'sql server' => ['sql server','mssql'],
        'oracle' => ['oracle'],
        'mongodb' => ['mongodb','mongo'],
        'sqlite' => ['sqlite'],
        'redis' => ['redis'],
        'firebase' => ['firebase'],
        'sql' => ['sql','structured query language'],

        // =========================
        // Cloud
        // =========================
        'aws' => ['aws','amazon web services'],
        'azure' => ['azure','microsoft azure'],
        'gcp' => ['gcp','google cloud','google cloud platform'],

        // =========================
        // DevOps
        // =========================
        'docker' => ['docker'],
        'kubernetes' => ['kubernetes','k8s'],
        'jenkins' => ['jenkins'],
        'git' => ['git','github','gitlab','bitbucket'],
        'ci/cd' => ['ci/cd','continuous integration','continuous deployment'],
        'linux' => ['linux','ubuntu','centos'],
        'nginx' => ['nginx'],
        'apache' => ['apache'],

        // =========================
        // Testing
        // =========================
        'manual testing' => ['manual testing','manual tester'],
        'automation testing' => ['automation testing','selenium'],
        'qa' => ['qa','quality assurance','testing'],
        'jira' => ['jira'],
        'postman' => ['postman'],
        'api testing' => ['api testing','rest api testing'],
        'regression testing' => ['regression testing'],
        'smoke testing' => ['smoke testing'],
        'sanity testing' => ['sanity testing'],
        'stlc' => ['stlc'],
        'sdlc' => ['sdlc'],

        // =========================
        // Mobile
        // =========================
        'android' => ['android'],
        'ios' => ['ios'],
        'react native' => ['react native'],
        'flutter' => ['flutter'],
        'xamarin' => ['xamarin'],

        // =========================
        // Data
        // =========================
        'power bi' => ['power bi','powerbi'],
        'tableau' => ['tableau'],
        'excel' => ['excel','advanced excel'],
        'etl' => ['etl'],
        'data analysis' => ['data analysis','analytics'],

        // =========================
        // AI
        // =========================
        'machine learning' => ['machine learning','ml'],
        'deep learning' => ['deep learning','dl'],
        'artificial intelligence' => ['artificial intelligence','ai'],
        'nlp' => ['nlp','natural language processing'],
        'chatgpt' => ['chatgpt','openai'],
        'llm' => ['llm','large language model'],

        // =========================
        // HR
        // =========================
        'recruitment' => ['recruitment','hiring','talent acquisition','staffing','sourcing'],
        'payroll' => ['payroll','salary processing'],
        'hrms' => ['hrms','human resource management system'],
        'onboarding' => ['onboarding','employee onboarding'],
        'attendance' => ['attendance management'],
        'performance management' => ['performance management','appraisal','performance appraisal'],
        'employee engagement' => ['employee engagement'],
        'exit process' => ['exit interview','offboarding'],

        // =========================
        // Finance
        // =========================
        'accounting' => ['accounting','accounts'],
        'billing' => ['billing','invoice','invoicing'],
        'gst' => ['gst'],
        'taxation' => ['tax','taxation'],
        'audit' => ['audit','financial audit'],
        'tally' => ['tally'],

        // =========================
        // Procurement
        // =========================
        'procurement' => ['procurement','purchasing'],
        'purchase order' => ['purchase order','po'],
        'vendor management' => ['vendor management'],
        'inventory management' => ['inventory','stock management'],
        'warehouse management' => ['warehouse'],
        'logistics' => ['logistics','supply chain'],
        'material management' => ['material management'],

        // =========================
        // Project Management
        // =========================
        'project management' => ['project management'],
        'agile' => ['agile','scrum'],
        'scrum master' => ['scrum master'],
        'kanban' => ['kanban'],
        'pmp' => ['pmp'],
        'jira agile' => ['jira agile'],

        // =========================
        // Sales
        // =========================
        'business development' => ['business development','bdm'],
        'lead generation' => ['lead generation'],
        'client acquisition' => ['client acquisition'],
        'crm' => ['crm','customer relationship management'],
        'proposal writing' => ['proposal writing','rfp','rfq'],
        'sales' => ['sales'],
        'marketing' => ['marketing'],
        'digital marketing' => ['digital marketing','seo','sem'],

        // =========================
        // Security
        // =========================
        'cyber security' => ['cyber security','information security'],
        'network security' => ['network security'],
        'cctv' => ['cctv','video surveillance'],
        'access control' => ['access control'],
        'vms' => ['video management system'],
        'firewall' => ['firewall'],

        // =========================
        // Soft Skills
        // =========================
        'communication' => ['communication','verbal communication'],
        'leadership' => ['leadership','team lead','team leadership'],
        'problem solving' => ['problem solving'],
        'analytical skills' => ['analytical'],
        'time management' => ['time management'],
        'multitasking' => ['multitasking'],
        'presentation' => ['presentation','presentation skills'],
        'documentation' => ['documentation'],

        // =========================
        // Experience
        // =========================
        'intern' => ['intern','internship','trainee'],
        'fresher' => ['fresher','entry level']
    ];

    if (!isset($synonyms[$skill])) {
        return false;
    }

    foreach ($synonyms[$skill] as $word) {
        if (preg_match('/(?<!\w)' . preg_quote($word, '/') . '(?!\w)/i', $text)) {
            return true;
        }
    }

    return false;
}
 private function extractExperienceDetails($text)
{
    // ----------- EXPERIENCE SECTION FILTER -----------
 
$start = 0;
 
$expKeywords = [
'experience',
'work experience',
'professional experience',
'employment',
'internship',
'career history',
'employment history',
'work history'
];
 
// foreach ($expKeywords as $word) {
 
//     $pos = stripos($text, $word);
 
//     if ($pos !== false) {
//         $start = $pos;
//         break;
//     }
// }
// $text = substr($text, $start);
$start = false;
 
foreach ($expKeywords as $word) {
 
    $pos = stripos($text, $word);
 
    if ($pos !== false) {
        $start = $pos;
        break;
    }
}
 
// if ($start === false) {
 
//     return [
//         "jobs" => [],
//         "total" => "0 Years 0 Months"
//     ];
// }
if ($start === false) {

    // Use full resume text
    $text = $text;

} else {

    $text = substr($text, $start);

}
 
$text = substr($text, $start);
 
 
 
// ----------- END EXPERIENCE SECTION FILTER -----------
    $jobs = [];
    $totalMonths = 0;
 
    // Detect experience sections
    // $expKeywords = ['experience','work experience','professional experience','employment','internship'];
    $expKeywords = [
'experience',
'work experience',
'professional experience',
'employment',
'internship',
'career history',
'employment history',
'work history',
'professional background'
];
 
 
    // Detect date ranges
    // preg_match_all('/
    // ((jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\s*\d{4}|\d{4})
    // \s*(\-|–|to)\s*
    // (present|(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\s*\d{4}|\d{4})
    // /ix', $text, $matches);
   
// preg_match_all('/
// (since\s+)?
// ((jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\s*\d{4}|\d{4})
// \s*(to|-|–)?
// \s*
// (present|currently|(jan|feb|mar|apr|may|jun|jul|aug|sep|oct|nov|dec)\s*\d{4}|\d{4})?
// /ix', $text, $matches);
$text = preg_replace('/(\d{1,2})(st|nd|rd|th)/i', '$1', $text);
preg_match_all('/
(
(jan|feb|mar|apr|may|jun|jul|aug|sep|sept|oct|nov|dec)[a-z]*[\s\-\.]*\d{4}
|
\d{1,2}[\/\-\.]\d{4}
|
\d{4}
)
\s*(to|-|–|—)\s*
(
present|current|now
|
(jan|feb|mar|apr|may|jun|jul|aug|sep|sept|oct|nov|dec)[a-z]*[\s\-\.]*\d{4}
|
\d{1,2}[\/\-\.]\d{4}
|
\d{4}
)
/ix', $text, $matches);
    if (!empty($matches[0])) {
 
        foreach ($matches[0] as $range) {
 
            $parts = preg_split('/\s*(to|-|–)\s*/', $range);
 
            if (count($parts) < 2) continue;
 
            $from = trim($parts[0]);
            $to   = trim($parts[1]);
 
            // convert year-only to Jan
            if (preg_match('/^\d{4}$/', $from)) {
                $from .= " jan";
            }
 
            if (preg_match('/^\d{4}$/', $to)) {
                $to .= " jan";
            }
 
            $fromDate = strtotime($from);
 
            if (stripos($to, 'present') !== false) {
                $toDate = time();
            } else {
                $toDate = strtotime($to);
            }
 
            if (!$fromDate || !$toDate) continue;
 
            if ($toDate < $fromDate) continue;
 
            $months = floor(($toDate - $fromDate) / (60*60*24*30));
 
            $years  = floor($months / 12);
            $remMon = $months % 12;
 
            $jobs[] = [
                "from" => $from,
                "to" => $to,
                "years" => $years,
                "months" => $remMon
            ];
 
            // $totalMonths += $months;
        }
    }
 
    // remove duplicate ranges
    // $jobs = array_unique($jobs, SORT_REGULAR);
$uniqueJobs = [];
 
foreach ($jobs as $job) {
 
    $key = $job['from'].'-'.$job['to'];
 
    if (!isset($uniqueJobs[$key])) {
        $uniqueJobs[$key] = $job;
    }
 
}
 
$jobs = array_values($uniqueJobs);
$totalMonths = 0;
 
foreach ($jobs as $job) {
 
    $fromDate = strtotime($job['from']);
 
    if (stripos($job['to'], 'present') !== false) {
        $toDate = time();
    } else {
        $toDate = strtotime($job['to']);
    }
 
    $months = floor(($toDate - $fromDate) / (60*60*24*30));
$totalMonths += $months;
 
}
    $totalYears = floor($totalMonths / 12);
    $totalRem   = $totalMonths % 12;
 
    return [
        "jobs" => $jobs,
        "total" => $totalYears . " Years " . $totalRem . " Months"
    ];
}
 
   private function logStage($stage, $data)
    {
        $log = "\n==============================\n";
        $log .= date('Y-m-d H:i:s') . " | " . $stage . "\n";
 
        if (is_array($data)) {
            $log .= print_r($data, true);
        } else {
            $log .= $data;
        }
 
        file_put_contents(
            APPPATH . 'logs/ats_debug.log',
            $log,
            FILE_APPEND
        );
    }
 
}
 
 
 