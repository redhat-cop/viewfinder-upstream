<?php
/**
 * PDF Generation for Digital Sovereignty Readiness Assessment Results
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Start session to retrieve assessment data
session_start();

// Check if we have assessment data in session
if (!isset($_SESSION['assessment_data']) || empty($_SESSION['assessment_data'])) {
    die('No assessment data found. Please complete the assessment first.');
}

// Get assessment data from session
$assessmentData = $_SESSION['assessment_data'];

// Load questions configuration
$questions = require_once 'config.php';

// Initialize scoring arrays
$totalScore = 0;
$maxScore = 21;
$domainScores = [];
$domainMaxScores = [];
$domainWeightedScores = [];
$domainResponses = [];
$unknownQuestions = [];

// Initialize domain scores
foreach ($questions as $domainName => $domainData) {
    $domainScores[$domainName] = 0;
    $domainMaxScores[$domainName] = count($domainData['questions']);
    $domainResponses[$domainName] = [];
}

// Calculate scores
foreach ($assessmentData as $key => $value) {
    // Match question IDs (ds1, ts1, os1, etc.)
    if (preg_match('/^(ds|ts|os|as|oss|eo|ms)\d+$/', $key)) {
        // Find which domain this question belongs to
        foreach ($questions as $domainName => $domainData) {
            foreach ($domainData['questions'] as $question) {
                if ($question['id'] === $key) {
                    // Handle "Don't Know" responses
                    if ($value === 'unknown') {
                        $unknownQuestions[] = [
                            'domain' => $domainName,
                            'question' => $question['text'],
                            'tooltip' => $question['tooltip'] ?? ''
                        ];
                    } else {
                        $intValue = intval($value);
                        $totalScore += $intValue;
                        $domainScores[$domainName] += $intValue;
                        // Track "Yes" responses (value > 0)
                        if ($intValue > 0) {
                            $domainResponses[$domainName][] = $question['text'];
                        }
                    }
                    break 2;
                }
            }
        }
    }
}

// Determine maturity level based on total score (4-level system)
if ($totalScore <= 5) {
    $maturityLevel = 'Foundation';
    $maturityColor = '#c9190b';
    $maturityIcon = '🌱';
    $recommendationDetail = 'Your organization is in the early stages of digital sovereignty awareness. There are significant dependencies on external providers and limited control over data and infrastructure.';
} elseif ($totalScore <= 10) {
    $maturityLevel = 'Developing';
    $maturityColor = '#ec7a08';
    $maturityIcon = '📈';
    $recommendationDetail = 'Your organization is actively building digital sovereignty capabilities. Some practices are in place, but there is room for improvement in control and independence.';
} elseif ($totalScore <= 16) {
    $maturityLevel = 'Strategic';
    $maturityColor = '#f0ab00';
    $maturityIcon = '📊';
    $recommendationDetail = 'Your organization has established strong capabilities in digital sovereignty. Key controls and processes are in place, with clear governance and data management practices.';
} else {
    $maturityLevel = 'Advanced';
    $maturityColor = '#2aaa04';
    $maturityIcon = '🛡️';
    $recommendationDetail = 'Your organization demonstrates comprehensive capabilities in digital sovereignty. You have strong control over data, infrastructure, and decision-making with minimal external dependencies.';
}

// Calculate percentage based on total score
$scorePercentage = round(($totalScore / $maxScore) * 100);
$assessmentDate = date('F j, Y \a\t g:i A');

// Build HTML for PDF
$html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Digital Sovereignty Readiness Assessment Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            font-size: 11pt;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid ' . $maturityColor . ';
            padding-bottom: 20px;
        }
        .header h1 {
            color: #151515;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .header .date {
            color: #666;
            font-size: 11px;
        }
        .score-card {
            background: ' . $maturityColor . ';
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        .score-card h2 {
            margin: 0 0 15px 0;
            font-size: 26px;
        }
        .score-circle {
            font-size: 42px;
            font-weight: bold;
            margin: 15px 0;
        }
        .score-detail {
            font-size: 13px;
            opacity: 0.9;
        }
        .recommendation {
            margin: 15px 0;
            font-size: 13px;
            line-height: 1.8;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section h3 {
            color: ' . $maturityColor . ';
            border-bottom: 2px solid ' . $maturityColor . ';
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th {
            background: #f5f5f5;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            font-size: 10pt;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 10pt;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            font-size: 10px;
        }
        .badge-foundation { background: #c9190b; }
        .badge-developing { background: #ec7a08; }
        .badge-strategic { background: #f0ab00; color: #000; }
        .badge-advanced { background: #2aaa04; }
        .unknown-list {
            margin: 15px 0;
        }
        .unknown-item {
            background: #f9f9f9;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #0066cc;
        }
        .unknown-item strong {
            display: block;
            margin-bottom: 5px;
            color: #0066cc;
            font-size: 11pt;
        }
        .improvement-section {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid ' . $maturityColor . ';
            margin: 20px 0;
            page-break-inside: avoid;
        }
        .improvement-section h4 {
            margin-top: 0;
            color: ' . $maturityColor . ';
            font-size: 14px;
        }
        .improvement-section ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .improvement-section li {
            margin: 8px 0;
            font-size: 10pt;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 9pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Digital Sovereignty Readiness Assessment Results</h1>
        <div class="date">Assessment Date: ' . htmlspecialchars($assessmentDate) . '</div>
    </div>

    <div class="score-card">
        <h2>' . htmlspecialchars($maturityLevel) . ' Maturity Level</h2>
        <div class="score-circle">' . $scorePercentage . '%</div>
        <div class="score-detail">' . $totalScore . ' of ' . $maxScore . ' points</div>
        <div class="recommendation">' . htmlspecialchars($recommendationDetail) . '</div>
    </div>

    <div class="section" style="background: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <h3 style="margin-top: 0; color: #333; border-bottom: none; font-size: 14px;">Profile Information</h3>
        <p style="margin: 5px 0; font-size: 11pt;"><strong>' . htmlspecialchars($profileData['name']) . '</strong></p>
        <p style="margin: 5px 0; color: #666; font-size: 10pt;">' . htmlspecialchars($profileData['description']) . '</p>
    </div>

    <div class="section">
        <h3>Domain Analysis</h3>
        <p style="font-size: 10pt; color: #666; margin: 10px 0;">Weights reflect the importance of each domain for the <strong>' . htmlspecialchars($profileData['name']) . '</strong> profile. Domains with higher weights (\u2265\u00a01.5\u00d7) contribute more to your overall score.</p>
        <table>
            <thead>
                <tr>
                    <th>Domain</th>
                    <th style="text-align: center;">Score</th>
                    <th style="text-align: center;">Weight</th>
                    <th style="text-align: center;">Percentage</th>
                    <th>Maturity Level</th>
                </tr>
            </thead>
            <tbody>';

foreach ($questions as $domainName => $domainData) {
    $score = $domainScores[$domainName] ?? 0;
    $maxDomainScore = count($domainData['questions']);
    $percentage = $maxDomainScore > 0 ? round(($score / $maxDomainScore) * 100) : 0;
    $weight = $domainWeights[$domainName] ?? 1.0;

    if ($percentage == 0) {
        $badge = 'foundation';
        $levelText = 'Foundation';
    } elseif ($percentage <= 33) {
        $badge = 'developing';
        $levelText = 'Developing';
    } elseif ($percentage <= 67) {
        $badge = 'strategic';
        $levelText = 'Strategic';
    } else {
        $badge = 'advanced';
        $levelText = 'Advanced';
    }

    $weightStyle = $weight >= 1.5 ? 'background: #f0ab00; color: #fff; font-weight: bold;' : 'background: #f5f5f5; color: #333;';

    $html .= '<tr>
                <td><strong>' . htmlspecialchars($domainName) . '</strong></td>
                <td style="text-align: center;">' . $score . '/' . $maxDomainScore . '</td>
                <td style="text-align: center;"><span style="display: inline-block; padding: 3px 8px; border-radius: 3px; ' . $weightStyle . '">' . number_format($weight, 1) . '\u00d7</span></td>
                <td style="text-align: center;">' . $percentage . '%</td>
                <td><span class="badge badge-' . $badge . '">' . $levelText . '</span></td>
              </tr>';
}

$html .= '  </tbody>
        </table>
    </div>';

// Recommended Improvement Actions section
$html .= '<div class="section">
    <h3>Recommended Improvement Actions</h3>';

if ($maturityLevel === 'Foundation') {
    $html .= '<div class="improvement-section">
        <h4>Critical Actions for Foundation Level</h4>
        <p>Your organization is in the early stages of digital sovereignty awareness. Focus on building foundational capabilities:</p>
        <ul>
            <li><strong>Gain Executive Awareness:</strong> Educate leadership on digital sovereignty risks and regulatory requirements</li>
            <li><strong>Assess Current State:</strong> Conduct inventory of data locations, vendor dependencies, and compliance gaps</li>
            <li><strong>Identify Quick Wins:</strong> Address immediate sovereignty risks (e.g., data residency violations, unencrypted data)</li>
            <li><strong>Secure Resources:</strong> Obtain initial budget and staffing for sovereignty initiatives</li>
            <li><strong>Define Initial Policies:</strong> Create basic policies for data handling and vendor selection</li>
            <li><strong>Build Awareness:</strong> Launch awareness campaigns to educate staff about digital sovereignty</li>
        </ul>
        <h4>Immediate Priorities:</h4>
        <ul>
            <li>Executive sponsorship and steering committee formation</li>
            <li>Critical data classification and residency mapping</li>
            <li>Vendor dependency assessment</li>
            <li>Compliance requirement documentation (GDPR, NIS2, etc.)</li>
        </ul>
    </div>';
} elseif ($maturityLevel === 'Developing') {
    $html .= '<div class="improvement-section">
        <h4>Growth Actions for Developing Level</h4>
        <p>Your organization is actively building capabilities. Focus on establishing repeatable practices and controls:</p>
        <ul>
            <li><strong>Develop Strategy:</strong> Create a digital sovereignty roadmap aligned with business objectives</li>
            <li><strong>Implement Controls:</strong> Deploy encryption key management (BYOK/HYOK) and data residency controls</li>
            <li><strong>Establish Governance:</strong> Form sovereignty governance committee with clear responsibilities</li>
            <li><strong>Document Procedures:</strong> Create standard operating procedures for sovereignty-critical activities</li>
            <li><strong>Build Capabilities:</strong> Train technical teams on sovereign technologies and frameworks</li>
            <li><strong>Evaluate Solutions:</strong> Research open-source and sovereign-ready platforms</li>
        </ul>
        <h4>Key Focus Areas:</h4>
        <ul>
            <li>Data sovereignty and encryption controls</li>
            <li>Repeatable assessment processes</li>
            <li>Vendor risk management framework</li>
            <li>Compliance tracking and reporting</li>
        </ul>
    </div>';
} elseif ($maturityLevel === 'Strategic') {
    $html .= '<div class="improvement-section">
        <h4>Advancement Actions for Strategic Level</h4>
        <p>Your organization has strong capabilities. Focus on optimization and organization-wide consistency:</p>
        <ul>
            <li><strong>Standardize Processes:</strong> Ensure sovereignty practices are consistent across all business units</li>
            <li><strong>Implement Standards:</strong> Adopt open standards and containerization for portability</li>
            <li><strong>Enhance Controls:</strong> Implement advanced monitoring, audit rights, and security log sovereignty</li>
            <li><strong>Build Resilience:</strong> Develop and test disaster recovery plans for geopolitical scenarios</li>
            <li><strong>Expand Open Source:</strong> Increase use of open-source software and contribute to strategic projects</li>
            <li><strong>Pursue Certifications:</strong> Obtain relevant certifications (NIS2, SecNumCloud, FedRAMP, etc.)</li>
        </ul>
        <h4>Advancement Priorities:</h4>
        <ul>
            <li>Process standardization and documentation</li>
            <li>Cloud platform portability testing</li>
            <li>Organization-wide training programs</li>
            <li>Sovereignty metrics and KPIs definition</li>
        </ul>
    </div>';
} else {
    $html .= '<div class="improvement-section">
        <h4>Excellence Actions for Advanced Level</h4>
        <p>Your organization demonstrates comprehensive capabilities. Focus on continuous improvement and innovation:</p>
        <ul>
            <li><strong>Drive Innovation:</strong> Pilot and deploy innovative sovereignty technologies and practices</li>
            <li><strong>Continuous Improvement:</strong> Continuously optimize processes based on metrics and feedback</li>
            <li><strong>Share Knowledge:</strong> Document and share best practices with industry and open-source communities</li>
            <li><strong>Lead Standards:</strong> Contribute to and influence digital sovereignty standards and frameworks</li>
            <li><strong>Expand Scope:</strong> Apply sovereignty principles to emerging technologies (AI, edge, quantum)</li>
            <li><strong>Stay Ahead:</strong> Proactively monitor and adapt to evolving regulations and geopolitical changes</li>
        </ul>
        <h4>Leadership Focus:</h4>
        <ul>
            <li>Industry thought leadership and advocacy</li>
            <li>Advanced analytics and metrics dashboards</li>
            <li>Continuous monitoring and improvement</li>
            <li>Innovation in sovereignty technologies</li>
        </ul>
    </div>';
}

$html .= '</div>';

// Detailed Domain Insights section
$html .= '<div class="section">
    <h3>Detailed Domain Insights</h3>
    <p style="font-size: 10pt; margin-bottom: 15px;">Review your specific responses across all domains:</p>';

$hasAnyRequirements = false;
foreach ($questions as $domainName => $domainData) {
    $score = $domainScores[$domainName] ?? 0;
    $responses = $domainResponses[$domainName] ?? [];

    if ($score > 0) {
        $hasAnyRequirements = true;
        $html .= '<div style="background: #f9f9f9; padding: 12px; margin: 12px 0; border-left: 4px solid ' . $maturityColor . '; page-break-inside: avoid;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <h4 style="margin: 0; color: #333; font-size: 13pt;">' . htmlspecialchars($domainName) . '</h4>
                <span style="background: ' . $maturityColor . '; color: white; padding: 4px 10px; border-radius: 4px; font-weight: bold; font-size: 11pt;">' . $score . '/' . count($domainData['questions']) . '</span>
            </div>
            <p style="margin: 8px 0; color: #666; font-size: 10pt;">' . htmlspecialchars($domainData['description']) . '</p>
            <div style="margin-top: 10px;">
                <strong style="font-size: 10pt; color: #333;">Requirements Identified:</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">';

        foreach ($responses as $response) {
            $html .= '<li style="margin: 4px 0; font-size: 10pt; color: #333;">' . htmlspecialchars($response) . '</li>';
        }

        $html .= '</ul>
            </div>
        </div>';
    }
}

if (!$hasAnyRequirements) {
    $html .= '<p style="padding: 15px; background: #f9f9f9; border-left: 4px solid #0066cc; margin: 10px 0; font-size: 10pt;">
                <strong>No Digital Sovereignty requirements were identified in this assessment.</strong> Consider focusing on other value propositions.
              </p>';
}

$html .= '</div>';

// Questions to Research section
if (!empty($unknownQuestions)) {
    $html .= '<div class="section">
        <h3>Questions to Research</h3>
        <p>The following questions were marked as "Don\'t Know". Research these areas to get a complete picture of your organization\'s Digital Sovereignty readiness:</p>
        <div class="unknown-list">';

    $unknownByDomain = [];
    foreach ($unknownQuestions as $uq) {
        $unknownByDomain[$uq['domain']][] = $uq;
    }

    foreach ($unknownByDomain as $domainName => $domainUnknowns) {
        $html .= '<h4 style="color: #0066cc; margin-top: 15px;">' . htmlspecialchars($domainName) . '</h4>';
        foreach ($domainUnknowns as $uq) {
            $html .= '<div class="unknown-item">
                        <strong>' . htmlspecialchars($uq['question']) . '</strong>';
            if (!empty($uq['tooltip'])) {
                $html .= '<p style="margin: 5px 0 0 0; font-size: 10pt; color: #666;">' . htmlspecialchars($uq['tooltip']) . '</p>';
            }
            $html .= '</div>';
        }
    }

    $html .= '</div></div>';
}

$html .= '
    <div class="footer">
        <p>Generated by Viewfinder Lite - Digital Sovereignty Readiness Assessment</p>
        <p>' . htmlspecialchars($assessmentDate) . '</p>
    </div>
</body>
</html>';

// Configure Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', 'Arial');

// Initialize Dompdf
$dompdf = new Dompdf($options);

// Load HTML content
$dompdf->loadHtml($html);

// Set paper size
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output PDF for download
$filename = 'DS-Readiness-Assessment-' . date('Y-m-d-His') . '.pdf';
$dompdf->stream($filename, ['Attachment' => true]);
