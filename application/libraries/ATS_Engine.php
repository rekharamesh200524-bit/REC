<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

class ATS_Engine {

    public function processResume($file, $vacancy)
    {
        $this->logStage('START', [
            'resumePath' => $file,
            'vacancyId'  => $vacancy['Jid'] ?? null,
            'jobTitle'   => $vacancy['JobTitle'] ?? null
        ]);

        // 1. EXTRACT RAW RESUME TEXT
        $rawText = (string)$this->extractText($file);
        $text    = strtolower($rawText);

        $this->logStage('RAW_RESUME_TEXT', substr($text, 0, 2000));

        // 2. EXTRACT WORK EXPERIENCE DETAILS
        $experienceDetails = $this->extractExperienceDetails($text);
        $this->logStage('EXPERIENCE_DETAILS', $experienceDetails);

        // 3. EXTRACT CONTACT / PERSONAL INFORMATION
        $email = '';
        if (preg_match('/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', $rawText, $matches)) {
            $email = strtolower($matches[0]);
        }

        // Extract Candidate Name
        $name  = '';
        $lines = preg_split('/\r\n|\r|\n/', $rawText);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line == '' || preg_match('/^\d+$/', $line)) continue;
            if (filter_var($line, FILTER_VALIDATE_EMAIL)) continue;
            if (preg_match('/(\+91|91)?\s?[6-9]\d{9}/', $line)) continue;
            if (preg_match('/career|objective|summary|profile|education|experience|skills|projects/i', $line)) continue;

            $cleanedLine = preg_replace('/\s+/', ' ', $line);
            if (preg_match('/^([A-Za-z][A-Za-z.\s]{2,40})/', $cleanedLine, $m)) {
                $candidateName = trim($m[1]);
                if (!preg_match('/road|street|nagar|district|state|india|tamil nadu|chennai|mumbai|delhi|bangalore|hyderabad/i', $candidateName)) {
                    $name = ucwords(strtolower($candidateName));
                    break;
                }
            }
        }

        // Extract Mobile Number(s)
        $mobileNumbers = null;
        if (preg_match_all('/(\+?\s?91[\s\-]?)?[6-9]\d{9}/', $rawText, $matches)) {
            $uniqueMobiles = array_unique($matches[0]);
            $mobileNumbers = !empty($uniqueMobiles) ? implode(', ', $uniqueMobiles) : null;
        }

        // 4. EXTRACT TOTAL YEARS OF EXPERIENCE
        $candidateExp = 0.0;
        $expPatterns = [
            '/overall\s+(\d+(?:\.\d+)?)\+?\s*years?\s+of\s+experience/i',
            '/total\s+(\d+(?:\.\d+)?)\+?\s*years?\s+of\s+experience/i',
            '/(\d+(?:\.\d+)?)\+?\s*years?\s+of\s+experience/i',
            '/experience\s*[:\-]?\s*(\d+(?:\.\d+)?)/i'
        ];
        foreach ($expPatterns as $pattern) {
            if (preg_match($pattern, $rawText, $m)) {
                $candidateExp = (float)$m[1];
                break;
            }
        }

        // Fallback to experience details calculation if zero
        if ($candidateExp == 0.0 && !empty($experienceDetails['total'])) {
            if (preg_match('/(\d+)\s*Years/i', $experienceDetails['total'], $ym)) {
                $candidateExp += (float)$ym[1];
            }
            if (preg_match('/(\d+)\s*Months/i', $experienceDetails['total'], $mm)) {
                $candidateExp += round(((float)$mm[1]) / 12, 1);
            }
        }
        if ($candidateExp == 0.0 && stripos($text, 'intern') !== false) {
            $candidateExp = 1.0;
        }
        $expyrs = $candidateExp;

        $this->logStage('Email', $email);
        $this->logStage('Name', $name);
        $this->logStage('Mobile', $mobileNumbers);
        $this->logStage('ExperienceYears', $expyrs);

        // 5. EXTRACT ALL CANDIDATE SKILLS FROM DICTIONARY
        $allExtractedSkills = $this->extractAllSkillsFromText($text);

        // 6. ANALYZE VACANCY SKILLS
        $vacancySkillsRaw = '';
        if (!empty($vacancy['RequiredSkills'])) {
            $vacancySkillsRaw = $vacancy['RequiredSkills'];
        } elseif (!empty($vacancy['MustHaveSkills'])) {
            $vacancySkillsRaw = $vacancy['MustHaveSkills'] . (!empty($vacancy['NiceToHaveSkills']) ? ', ' . $vacancy['NiceToHaveSkills'] : '');
        } elseif (!empty($vacancy['Skills'])) {
            $vacancySkillsRaw = $vacancy['Skills'];
        }

        $requiredSkillsList = array_unique(array_filter(array_map('trim', explode(',', strtolower($vacancySkillsRaw)))));

        $matchedSkills = [];
        $missingSkills = [];

        foreach ($requiredSkillsList as $reqSkill) {
            if (!$reqSkill) continue;
            $found = false;

            if (preg_match('/\b' . preg_quote($reqSkill, '/') . '\b/i', $text)) {
                $matchedSkills[] = $reqSkill;
                $found = true;
            } elseif ($this->synonymMatch($reqSkill, $text)) {
                $matchedSkills[] = $reqSkill;
                $found = true;
            } elseif ($this->fuzzyMatch($reqSkill, $text)) {
                $matchedSkills[] = $reqSkill;
                $found = true;
            }

            if (!$found) {
                $missingSkills[] = $reqSkill;
            }
        }

        // 7. ANALYZE EDUCATION
        $eduRequired = strtolower(trim($vacancy['EducationRequired'] ?? 'Any'));
        $eduMatch = false;
        $detectedDegree = 'Not Found';

        $degreeGroups = [
            'Doctorate' => ['phd', 'doctorate', 'doctor of philosophy'],
            'Master'    => ['mtech', 'm.tech', 'me', 'm.e', 'mba', 'm.b.a', 'msc', 'm.sc', 'mca', 'm.c.a', 'mcom', 'm.com', 'ma', 'm.a', 'master', 'master of technology', 'master of engineering', 'master of science', 'master of business administration'],
            'Bachelor'  => ['btech', 'b.tech', 'be', 'b.e', 'bachelor', 'bsc', 'b.sc', 'bca', 'b.c.a', 'bcom', 'b.com', 'ba', 'b.a', 'bba', 'b.b.a', 'bachelor of technology', 'bachelor of engineering', 'bachelor of science', 'bachelor of commerce', 'bachelor of arts'],
            'Diploma'   => ['diploma', 'polytechnic']
        ];

        $normText = preg_replace('/[^a-z0-9 ]/', ' ', $text);
        $normText = preg_replace('/\s+/', ' ', $normText);

        if ($eduRequired === 'any' || empty($eduRequired)) {
            $eduMatch = true;
            $detectedDegree = 'Any qualification accepted';
        } else {
            foreach ($degreeGroups as $groupLevel => $degrees) {
                foreach ($degrees as $deg) {
                    if (stripos($normText, $deg) !== false) {
                        $detectedDegree = strtoupper($deg);
                        if (stripos($eduRequired, strtolower($groupLevel)) !== false || stripos($eduRequired, $deg) !== false || $eduRequired === 'any') {
                            $eduMatch = true;
                            break 2;
                        }
                    }
                }
            }
            if (!$eduMatch) {
                // Direct substring fallback check
                $reqParts = explode(',', $eduRequired);
                foreach ($reqParts as $part) {
                    $part = trim($part);
                    if (!empty($part) && stripos($normText, $part) !== false) {
                        $eduMatch = true;
                        $detectedDegree = strtoupper($part);
                        break;
                    }
                }
            }
        }

        // 8. ANALYZE DOMAIN / ROLE FIT
        $domainName = $this->detectDomain($text, $vacancy);

        // 9. EXPERIENCE COMPARISON
        $minExpRequired = (float)($vacancy['ExpMin'] ?? 0.0);
        $expMatch = ($candidateExp >= $minExpRequired || $minExpRequired == 0.0);

        // 10. EVIDENCE-BASED RECOMMENDATION EVALUATION ENGINE
        $relevantEvidence    = [];
        $missingRequirements = [];

        // Build Evidence
        if ($expyrs > 0) {
            $relevantEvidence[] = "Work Experience: {$expyrs} Years total experience" . ($minExpRequired > 0 ? " (Vacancy Required: Min {$minExpRequired} Years)" : "");
        } elseif ($minExpRequired == 0) {
            $relevantEvidence[] = "Entry level / Fresher candidate (Vacancy accepts fresher)";
        }

        if (!empty($matchedSkills)) {
            $relevantEvidence[] = "Matched Core Skills: " . implode(', ', array_map('ucwords', array_slice($matchedSkills, 0, 10)));
        }

        if ($eduMatch) {
            $relevantEvidence[] = "Education: Matches required level" . ($detectedDegree !== 'Not Found' ? " ({$detectedDegree})" : "");
        }

        if ($domainName && $domainName !== 'General') {
            $relevantEvidence[] = "Domain Fit: Background in {$domainName}";
        }

        if (!empty($allExtractedSkills)) {
            $relevantEvidence[] = "Extracted Resume Skills: " . implode(', ', array_map('ucwords', array_slice($allExtractedSkills, 0, 8)));
        }

        // Build Missing Requirements
        if (!empty($missingSkills)) {
            $missingRequirements[] = "Required Skills Not Found: " . implode(', ', array_map('ucwords', $missingSkills));
        }

        if ($minExpRequired > 0 && $candidateExp < $minExpRequired) {
            $missingRequirements[] = "Experience Shortfall: Candidate has {$candidateExp} Years (Required: Min {$minExpRequired} Years)";
        }

        if (!$eduMatch && $eduRequired !== 'any') {
            $missingRequirements[] = "Education Gap: Required qualification ({$vacancy['EducationRequired']}) not clearly identified in resume";
        }

        // 11. DETERMINE RECOMMENDATION CATEGORY
        $totalReqCount = count($requiredSkillsList);
        $matchedCount  = count($matchedSkills);
        $skillMatchRatio = $totalReqCount > 0 ? ($matchedCount / $totalReqCount) : 1.0;

        $jobTitleStr = strtolower($vacancy['JobTitle'] ?? '');
        $roleMatch = false;
        if (!empty($jobTitleStr)) {
            $titleWords = array_filter(explode(' ', preg_replace('/[^a-z0-9 ]/', '', $jobTitleStr)), function($w) {
                return strlen($w) > 2 && !in_array($w, ['executive', 'manager', 'lead', 'senior', 'junior', 'associate', 'trainee', 'developer', 'engineer', 'specialist', 'officer']);
            });
            foreach ($titleWords as $word) {
                if (stripos($text, $word) !== false) {
                    $roleMatch = true;
                    break;
                }
            }
        }

        if (($expMatch || $minExpRequired <= 1.0) && ($skillMatchRatio >= 0.5 || $totalReqCount == 0) && ($eduMatch || $eduRequired === 'any')) {
            $recommendation = 'Recommended';
            $recommendationReason = "The candidate's profile aligns well with the requirements of " . ($vacancy['JobTitle'] ?? 'the position') . ". ";
            if ($expyrs > 0) {
                $recommendationReason .= "They possess {$expyrs} years of relevant experience" . (!empty($matchedSkills) ? " and demonstrate key skills in " . implode(', ', array_map('ucwords', array_slice($matchedSkills, 0, 4))) : "") . ". ";
            } else {
                $recommendationReason .= "They demonstrate the required foundational qualifications and skills. ";
            }
            $recommendationReason .= "Their background provides concrete evidence for strong candidate suitability.";
        } elseif (($skillMatchRatio > 0 || $expyrs >= ($minExpRequired * 0.7) || $roleMatch) && count($missingRequirements) <= 2) {
            $recommendation = 'Review Required';
            $recommendationReason = "The candidate has a relevant background for " . ($vacancy['JobTitle'] ?? 'this role') . ", but specific requirements need verification during screening. ";
            if (!empty($missingSkills)) {
                $recommendationReason .= "Skill(s) like " . implode(', ', array_map('ucwords', array_slice($missingSkills, 0, 3))) . " were not clearly identified in the resume text. ";
            }
            if ($minExpRequired > 0 && $candidateExp < $minExpRequired) {
                $recommendationReason .= "Total experience ({$candidateExp} Yrs) is slightly below the preferred minimum ({$minExpRequired} Yrs). ";
            }
            $recommendationReason .= "Recruiter review and phone screening are recommended.";
        } else {
            $recommendation = 'Not Recommended';
            $recommendationReason = "The candidate's resume does not provide sufficient evidence of meeting core requirements for " . ($vacancy['JobTitle'] ?? 'this vacancy') . ". ";
            if (!empty($missingSkills)) {
                $recommendationReason .= "Key required skills (" . implode(', ', array_map('ucwords', array_slice($missingSkills, 0, 3))) . ") are missing. ";
            }
            if ($minExpRequired > 0 && $candidateExp < $minExpRequired) {
                $recommendationReason .= "Work experience ({$candidateExp} Yrs) falls substantially below the minimum required ({$minExpRequired} Yrs). ";
            }
            $recommendationReason .= "The profile is substantially unrelated or does not meet minimum thresholds.";
        }

        $this->logStage('RECOMMENDATION_ANALYSIS', [
            'matchedSkillsCount' => $matchedCount,
            'requiredSkillsCount' => $totalReqCount,
            'candidateExp' => $candidateExp,
            'minExpRequired' => $minExpRequired,
            'eduMatch' => $eduMatch
        ]);
        $this->logStage('RECOMMENDATION', $recommendation);
        $this->logStage('RECOMMENDATION_REASON', $recommendationReason);

        // 12. RETURN STRICT NO-SCORE EVALUATION RESULT
        return [
            'recommendation'        => $recommendation,
            'recommendation_reason' => $recommendationReason,
            'relevant_evidence'     => $relevantEvidence,
            'missing_requirements'  => $missingRequirements,
            'status'                => $recommendation, // Backward compatibility
            'email'                 => $email,
            'name'                  => $name,
            'mobileNumbers'         => $mobileNumbers,
            'expyrs'                => $expyrs,
            'experience_details'    => $experienceDetails,
            'domain'                => $domainName ?: 'General',
            'matched_skills'        => implode(', ', array_map('ucwords', $matchedSkills)),
            'missing_skills'        => implode(', ', array_map('ucwords', $missingSkills)),
            'all_extracted_skills'  => implode(', ', array_map('ucwords', array_unique($allExtractedSkills))),
            'education_match'       => $eduMatch ? 'Yes' : 'No',
            'experience'            => $expMatch ? 'Yes' : 'No',
            'detected_degree'       => $detectedDegree
        ];
    }

    private function extractAllSkillsFromText($text)
    {
        $foundSkills = [];
        $synonyms = $this->getSkillDictionary();

        foreach ($synonyms as $skill => $words) {
            foreach ($words as $word) {
                if (preg_match('/(?<!\w)' . preg_quote($word, '/') . '(?!\w)/i', $text)) {
                    $foundSkills[] = $skill;
                    break;
                }
            }
        }
        return array_unique($foundSkills);
    }

    private function detectDomain($text, $vacancy)
    {
        $vacancyDept = strtolower($vacancy['Departmentname'] ?? $vacancy['Department'] ?? '');
        $jobTitle    = strtolower($vacancy['JobTitle'] ?? '');

        $domains = [
            'Human Resources' => ['recruitment', 'hiring', 'talent acquisition', 'payroll', 'employee', 'hrms', 'onboarding', 'hr executive', 'hr manager'],
            'Finance & Accounts' => ['accounting', 'accounts', 'finance', 'billing', 'invoice', 'gst', 'taxation', 'audit', 'tally', 'accounts payable', 'accounts receivable'],
            'Sales & Marketing' => ['sales', 'business development', 'bdm', 'lead generation', 'client acquisition', 'crm', 'marketing', 'digital marketing', 'seo', 'sem', 'branding'],
            'Healthcare & Medical' => ['hospital', 'healthcare', 'medical', 'clinic', 'patient', 'pharmacy', 'ehr', 'emr', 'nursing', 'doctor'],
            'Software & IT' => ['software', 'developer', 'engineer', 'programmer', 'web development', 'frontend', 'backend', 'fullstack', 'database', 'cloud', 'devops', 'testing', 'qa'],
            'Operations & Logistics' => ['procurement', 'purchasing', 'supply chain', 'inventory', 'logistics', 'warehouse', 'operations manager', 'vendor management'],
            'Administration' => ['admin', 'administration', 'office assistant', 'facility management', 'front desk', 'receptionist'],
            'Customer Support' => ['customer support', 'customer service', 'call center', 'bpo', 'helpdesk', 'client support']
        ];

        // First check matching against vacancy department/title if available
        foreach ($domains as $domain => $keywords) {
            if (!empty($vacancyDept) && stripos($vacancyDept, strtolower(explode(' ', $domain)[0])) !== false) {
                return $domain;
            }
            if (!empty($jobTitle) && stripos($jobTitle, strtolower(explode(' ', $domain)[0])) !== false) {
                return $domain;
            }
        }

        // Next check resume text content
        foreach ($domains as $domain => $keywords) {
            foreach ($keywords as $word) {
                if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $text)) {
                    return $domain;
                }
            }
        }

        return 'General';
    }

    private function getSkillDictionary()
    {
        return [
            // Programming Languages
            'php' => ['php'],
            'javascript' => ['javascript', 'js', 'ecmascript'],
            'typescript' => ['typescript', 'ts'],
            'python' => ['python'],
            'java' => ['java'],
            'c#' => ['c#', 'csharp', '.net'],
            'c++' => ['c++'],
            'golang' => ['golang', 'go'],
            'ruby' => ['ruby'],
            'swift' => ['swift'],
            'kotlin' => ['kotlin'],

            // Frontend
            'html' => ['html', 'html5'],
            'css' => ['css', 'css3'],
            'bootstrap' => ['bootstrap'],
            'tailwind' => ['tailwind', 'tailwind css'],
            'sass' => ['sass', 'scss'],
            'jquery' => ['jquery'],
            'react' => ['react', 'reactjs', 'react.js'],
            'angular' => ['angular', 'angularjs'],
            'vue' => ['vue', 'vuejs'],
            'nextjs' => ['nextjs', 'next.js'],
            'nuxtjs' => ['nuxtjs', 'nuxt.js'],
            'redux' => ['redux'],
            'material ui' => ['material ui', 'mui'],

            // Backend
            'nodejs' => ['node', 'nodejs', 'node.js'],
            'express' => ['express', 'expressjs'],
            'laravel' => ['laravel'],
            'codeigniter' => ['codeigniter', 'ci'],
            'django' => ['django'],
            'flask' => ['flask'],
            'spring boot' => ['spring boot', 'springboot'],
            'dotnet' => ['.net', 'asp.net', 'dotnet'],
            'rest api' => ['rest', 'rest api', 'api'],
            'graphql' => ['graphql'],

            // Database
            'mysql' => ['mysql'],
            'postgresql' => ['postgres', 'postgresql'],
            'sql server' => ['sql server', 'mssql'],
            'oracle' => ['oracle'],
            'mongodb' => ['mongodb', 'mongo'],
            'sqlite' => ['sqlite'],
            'redis' => ['redis'],
            'firebase' => ['firebase'],
            'sql' => ['sql', 'structured query language'],

            // Cloud & DevOps
            'aws' => ['aws', 'amazon web services'],
            'azure' => ['azure', 'microsoft azure'],
            'gcp' => ['gcp', 'google cloud', 'google cloud platform'],
            'docker' => ['docker'],
            'kubernetes' => ['kubernetes', 'k8s'],
            'jenkins' => ['jenkins'],
            'git' => ['git', 'github', 'gitlab', 'bitbucket'],
            'ci/cd' => ['ci/cd', 'continuous integration', 'continuous deployment'],
            'linux' => ['linux', 'ubuntu', 'centos'],

            // HR
            'recruitment' => ['recruitment', 'hiring', 'talent acquisition', 'staffing', 'sourcing'],
            'payroll' => ['payroll', 'salary processing'],
            'hrms' => ['hrms', 'human resource management system'],
            'onboarding' => ['onboarding', 'employee onboarding'],
            'attendance' => ['attendance management'],
            'performance management' => ['performance management', 'appraisal', 'performance appraisal'],
            'employee relations' => ['employee relations'],
            'exit process' => ['exit interview', 'offboarding'],

            // Finance & Accounting
            'accounting' => ['accounting', 'accounts'],
            'billing' => ['billing', 'invoice', 'invoicing'],
            'gst' => ['gst'],
            'taxation' => ['tax', 'taxation'],
            'audit' => ['audit', 'financial audit'],
            'tally' => ['tally'],
            'financial reporting' => ['financial reporting', 'financial statements'],
            'accounts payable' => ['accounts payable'],
            'accounts receivable' => ['accounts receivable'],

            // Sales & Marketing
            'business development' => ['business development', 'bdm'],
            'lead generation' => ['lead generation'],
            'client acquisition' => ['client acquisition'],
            'crm' => ['crm', 'customer relationship management'],
            'sales' => ['sales'],
            'marketing' => ['marketing'],
            'digital marketing' => ['digital marketing'],
            'seo' => ['seo', 'search engine optimization'],
            'sem' => ['sem'],
            'content marketing' => ['content marketing'],
            'social media' => ['social media'],
            'negotiation' => ['negotiation'],

            // Healthcare
            'healthcare' => ['healthcare', 'medical', 'hospital'],
            'patient care' => ['patient care', 'patient management'],
            'pharmacy' => ['pharmacy'],
            'ehr' => ['ehr', 'emr'],

            // Procurement & Supply Chain
            'procurement' => ['procurement', 'purchasing'],
            'purchase order' => ['purchase order', 'po'],
            'vendor management' => ['vendor management'],
            'inventory management' => ['inventory', 'stock management'],
            'logistics' => ['logistics', 'supply chain'],

            // Project & Management
            'project management' => ['project management'],
            'agile' => ['agile', 'scrum'],
            'scrum master' => ['scrum master'],
            'kanban' => ['kanban'],
            'pmp' => ['pmp'],

            // Soft Skills & General
            'communication' => ['communication', 'verbal communication'],
            'leadership' => ['leadership', 'team lead', 'team leadership'],
            'problem solving' => ['problem solving'],
            'analytical skills' => ['analytical'],
            'time management' => ['time management'],
            'documentation' => ['documentation']
        ];
    }

    private function synonymMatch($skill, $text)
    {
        $skill = strtolower(trim($skill));
        $text  = strtolower($text);

        if (preg_match('/(?<!\w)' . preg_quote($skill, '/') . '(?!\w)/i', $text)) {
            return true;
        }

        $synonyms = $this->getSkillDictionary();

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

    private function fuzzyMatch($word, $text, $threshold = 70)
    {
        foreach (explode(' ', $text) as $w) {
            similar_text($word, $w, $percent);
            if ($percent >= $threshold) return true;
        }
        return false;
    }

    private function extractText($file)
    {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        try {
            if ($ext == 'txt') {
                return trim(file_get_contents($file));
            } elseif ($ext == 'docx') {
                return trim($this->readDocx($file));
            } elseif ($ext == 'pdf') {
                if (!file_exists($file)) {
                    $this->logStage('PDF_ERROR', 'PDF file not found : ' . $file);
                    return '';
                }
                $parser = new \Smalot\PdfParser\Parser();
                $pdf    = $parser->parseFile($file);
                $text   = $pdf->getText();

                if (empty(trim($text))) {
                    $this->logStage('PDF_ERROR', 'No text extracted from PDF.');
                    return '';
                }
                $this->logStage('PDF_EXTRACT_SUCCESS', substr($text, 0, 1000));
                return trim($text);
            } else {
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

    private function extractExperienceDetails($text)
    {
        $start = false;
        $expKeywords = [
            'experience', 'work experience', 'professional experience', 'employment',
            'internship', 'career history', 'employment history', 'work history', 'professional background'
        ];

        foreach ($expKeywords as $word) {
            $pos = stripos($text, $word);
            if ($pos !== false) {
                $start = $pos;
                break;
            }
        }

        if ($start !== false) {
            $text = substr($text, $start);
        }

        $jobs = [];
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
                $parts = preg_split('/\s*(to|-|–|—)\s*/', $range);
                if (count($parts) < 2) continue;

                $from = trim($parts[0]);
                $to   = trim($parts[1]);

                if (preg_match('/^\d{4}$/', $from)) $from .= " jan";
                if (preg_match('/^\d{4}$/', $to))   $to   .= " jan";

                $fromDate = strtotime($from);
                $toDate   = (stripos($to, 'present') !== false || stripos($to, 'current') !== false || stripos($to, 'now') !== false) ? time() : strtotime($to);

                if (!$fromDate || !$toDate || $toDate < $fromDate) continue;

                $months = floor(($toDate - $fromDate) / (60*60*24*30));
                $years  = floor($months / 12);
                $remMon = $months % 12;

                $jobs[] = [
                    "from" => $from,
                    "to"   => $to,
                    "years" => $years,
                    "months" => $remMon
                ];
            }
        }

        $uniqueJobs = [];
        foreach ($jobs as $job) {
            $key = $job['from'] . '-' . $job['to'];
            if (!isset($uniqueJobs[$key])) {
                $uniqueJobs[$key] = $job;
            }
        }
        $jobs = array_values($uniqueJobs);
        $totalMonths = 0;

        foreach ($jobs as $job) {
            $fromDate = strtotime($job['from']);
            $toDate   = (stripos($job['to'], 'present') !== false || stripos($job['to'], 'current') !== false || stripos($job['to'], 'now') !== false) ? time() : strtotime($job['to']);
            $months   = floor(($toDate - $fromDate) / (60*60*24*30));
            $totalMonths += $months;
        }

        $totalYears = floor($totalMonths / 12);
        $totalRem   = $totalMonths % 12;

        return [
            "jobs"  => $jobs,
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