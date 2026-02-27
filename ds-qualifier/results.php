<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Results - Digital Sovereignty Readiness Assessment</title>

  <!-- Reuse existing CSS from parent directory -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/brands.css" />
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/tab-dark.css" />
  <link rel="stylesheet" href="../css/patternfly.css" />
  <link rel="stylesheet" href="../css/patternfly-addons.css" />

  <!-- DS Qualifier specific styles -->
  <link rel="stylesheet" href="css/ds-qualifier.css" />

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #151515 !important;
      color: #ccc !important;
    }
    .pf-c-page__header-tools button {
      margin-right: 1rem;
    }
    .widget {
      padding-top: 1rem;
    }
    @media print {
      .no-print { display: none; }
      .score-card { page-break-after: avoid; }
    }
  </style>
</head>

<body>
  <header class="pf-c-page__header no-print">
    <div class="pf-c-page__header-brand">
      <div class="pf-c-page__header-brand-toggle"></div>
    </div>

    <div class="widget">
      <a href="../index.php"><button><i class="fa-solid fa-home"></i> Home</button></a>
      <a href="index.php"><button style="margin-left: 1rem;">New Assessment</button></a>
    </div>
  </header>

  <div class="container">
    <?php
    // Start session to store results for PDF generation
    session_start();

    // Store POST data in session for PDF generator
    $_SESSION['assessment_data'] = $_POST;

    // Load questions configuration for domain mapping
    $questions = require_once 'config.php';

    // Initialize scoring arrays
    $totalScore = 0;
    $maxScore = 21;
    $domainScores = [];
    $domainMaxScores = [];
    $domainResponses = [];
    $unknownQuestions = []; // Track "Don't Know" responses

    // Map domain keys to display names
    $domainKeyMap = [];
    foreach ($questions as $domainName => $domainData) {
        $domainKeyMap[$domainData['domain_key']] = $domainName;
        $domainScores[$domainName] = 0;
        $domainMaxScores[$domainName] = count($domainData['questions']);
        $domainResponses[$domainName] = [];
    }

    // Calculate scores
    foreach ($_POST as $key => $value) {
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
                            // Don't count toward score, but don't penalize either
                        } else {
                            $intValue = intval($value);
                            $totalScore += $intValue;
                            $domainScores[$domainName] += $intValue;
                            // Only add to responses if answer was "Yes" (value > 0)
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
        $priorityClass = 'maturity-foundation';
        $priorityIcon = 'fa-seedling';
        $recommendation = 'Foundation Level';
        $recommendationDetail = 'Your organization is in the early stages of digital sovereignty readiness. Focus on building awareness, assessing current dependencies, and establishing basic policies and governance structures.';
    } elseif ($totalScore <= 10) {
        $maturityLevel = 'Developing';
        $priorityClass = 'maturity-developing';
        $priorityIcon = 'fa-arrow-trend-up';
        $recommendation = 'Developing Level';
        $recommendationDetail = 'Your organization is actively building digital sovereignty capabilities. Continue implementing controls, developing strategies, and expanding capabilities across key domains.';
    } elseif ($totalScore <= 16) {
        $maturityLevel = 'Strategic';
        $priorityClass = 'maturity-strategic';
        $priorityIcon = 'fa-chart-line';
        $recommendation = 'Strategic Level';
        $recommendationDetail = 'Your organization has established strong digital sovereignty capabilities. Focus on standardization, advanced controls, and organization-wide consistency.';
    } else {
        $maturityLevel = 'Advanced';
        $priorityClass = 'maturity-advanced';
        $priorityIcon = 'fa-shield-halved';
        $recommendation = 'Advanced Level';
        $recommendationDetail = 'Your organization demonstrates advanced digital sovereignty capabilities across all domains. Continue maintaining excellence and stay ahead of evolving regulatory and geopolitical requirements.';
    }

    $assessmentDate = date('F j, Y \a\t g:i A');
    ?>

    <!-- Results Header -->
    <div class="results-header">
      <h1><i class="fa-solid fa-chart-bar"></i> Digital Sovereignty Readiness Assessment Results</h1>
      <p class="assessment-date"><strong>Assessment Date:</strong> <?php echo $assessmentDate; ?></p>
    </div>

    <!-- Score Card -->
    <div class="score-card <?php echo $priorityClass; ?>">
      <div class="score-icon">
        <i class="fa-solid <?php echo $priorityIcon; ?>"></i>
      </div>
      <h2><?php echo $maturityLevel; ?> Maturity Level</h2>

      <?php
      // Calculate percentage for visual display (based on total score)
      $scorePercentage = round(($totalScore / $maxScore) * 100);
      ?>

      <div class="score-visual-container">
        <div class="circular-progress" data-percentage="<?php echo $scorePercentage; ?>">
          <svg class="progress-ring" width="200" height="200">
            <circle class="progress-ring-circle-bg" cx="100" cy="100" r="90" />
            <circle class="progress-ring-circle"
                    cx="100"
                    cy="100"
                    r="90"
                    style="stroke-dasharray: <?php echo 2 * 3.14159 * 90; ?>; stroke-dashoffset: <?php echo 2 * 3.14159 * 90 * (1 - $scorePercentage / 100); ?>;" />
          </svg>
          <div class="progress-text">
            <div class="percentage-display"><?php echo $scorePercentage; ?>%</div>
            <div class="score-detail">
              <strong><?php echo $totalScore; ?></strong> of <?php echo $maxScore; ?> points
            </div>
          </div>
        </div>
      </div>

      <h3 class="recommendation-title"><?php echo $recommendation; ?></h3>
      <p class="recommendation-detail"><?php echo $recommendationDetail; ?></p>
    </div>

    <!-- Domain Breakdown -->
    <div class="domain-breakdown">
      <h2><i class="fa-solid fa-table"></i> Domain Analysis</h2>
      <p class="section-intro">Breakdown of your readiness across the 7 Digital Sovereignty domains:</p>

      <div class="domain-table-wrapper">
        <table class="domain-table">
          <thead>
            <tr>
              <th>Domain</th>
              <th style="text-align: center;">Score</th>
              <th style="text-align: center;">Progress</th>
              <th>Maturity Level</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($questions as $domainName => $domainData):
                $score = $domainScores[$domainName] ?? 0;
                $maxDomainScore = count($domainData['questions']);
                $percentage = ($score / $maxDomainScore) * 100;

                // Maturity levels based on score percentage (4-level system)
                if ($percentage == 0) {
                    $strengthClass = 'strength-foundation';
                    $strengthIcon = 'fa-seedling';
                    $strengthText = 'Foundation';
                } elseif ($percentage <= 33) {
                    $strengthClass = 'strength-developing';
                    $strengthIcon = 'fa-arrow-trend-up';
                    $strengthText = 'Developing';
                } elseif ($percentage <= 67) {
                    $strengthClass = 'strength-strategic';
                    $strengthIcon = 'fa-chart-line';
                    $strengthText = 'Strategic';
                } else {
                    $strengthClass = 'strength-advanced';
                    $strengthIcon = 'fa-shield-halved';
                    $strengthText = 'Advanced';
                }
            ?>
              <tr>
                <td><strong><?php echo htmlspecialchars($domainName); ?></strong></td>
                <td style="text-align: center;">
                  <span class="domain-score-cell"><?php echo $score; ?>/<?php echo $maxDomainScore; ?></span>
                </td>
                <td style="text-align: center;">
                  <span class="progress-bar-wrapper">
                    <div class="progress-bar">
                      <div class="progress-fill <?php echo $strengthClass; ?>" style="width: <?php echo $percentage; ?>%;"></div>
                    </div>
                  </span>
                </td>
                <td>
                  <span class="strength-badge <?php echo $strengthClass; ?>">
                    <i class="fa-solid <?php echo $strengthIcon; ?>"></i> <?php echo $strengthText; ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Questions to Research -->
    <?php if (!empty($unknownQuestions)): ?>
    <div class="unknown-questions-section">
      <h2><i class="fa-solid fa-clipboard-question"></i> Questions to Research</h2>
      <p class="section-description">
        The following questions were marked as "Don't Know". Research these areas to get a complete picture
        of your organization's Digital Sovereignty readiness and identify opportunities for improvement.
      </p>

      <?php
      // Group unknown questions by domain
      $unknownByDomain = [];
      foreach ($unknownQuestions as $uq) {
        $unknownByDomain[$uq['domain']][] = $uq;
      }
      ?>

      <div class="unknown-questions-list">
        <?php foreach ($unknownByDomain as $domainName => $domainUnknowns): ?>
          <div class="unknown-domain-section">
            <h3><i class="fa-solid fa-folder-open"></i> <?php echo htmlspecialchars($domainName); ?></h3>
            <ul class="unknown-question-items">
              <?php foreach ($domainUnknowns as $uq): ?>
                <li class="unknown-question-item">
                  <span class="question-icon"><i class="fa-solid fa-question-circle"></i></span>
                  <div class="question-content">
                    <div class="question-text"><?php echo htmlspecialchars($uq['question']); ?></div>
                    <?php if (!empty($uq['tooltip'])): ?>
                      <div class="question-context">
                        <i class="fa-solid fa-lightbulb"></i>
                        <strong>Context:</strong> <?php echo htmlspecialchars($uq['tooltip']); ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="discovery-tip">
        <i class="fa-solid fa-circle-info"></i>
        <strong>Tip:</strong> Understanding these areas will help you identify gaps in your digital sovereignty posture
        and prioritize improvements to strengthen your organization's independence and resilience.
      </div>
    </div>
    <?php endif; ?>

    <!-- Improvement Actions -->
    <div class="improvement-actions">
      <h2><i class="fa-solid fa-bullseye"></i> Recommended Improvement Actions</h2>

      <?php if ($maturityLevel === 'Foundation'): ?>
        <div class="action-priority maturity-foundation">
          <h3><i class="fa-solid fa-seedling"></i> Foundation Level Actions</h3>
          <p>Build foundational digital sovereignty awareness and establish initial governance:</p>
          <ul>
            <li><strong>Gain Executive Awareness:</strong> Educate leadership on digital sovereignty risks and regulatory requirements</li>
            <li><strong>Assess Current State:</strong> Conduct inventory of data locations, vendor dependencies, and compliance gaps</li>
            <li><strong>Identify Quick Wins:</strong> Address immediate sovereignty risks (e.g., data residency violations)</li>
            <li><strong>Secure Resources:</strong> Obtain initial budget and staffing for sovereignty initiatives</li>
            <li><strong>Define Initial Policies:</strong> Create basic policies for data handling and vendor selection</li>
            <li><strong>Build Awareness:</strong> Launch awareness campaigns to educate staff about digital sovereignty</li>
          </ul>

          <div class="recommended-products">
            <h4>Immediate Priorities:</h4>
            <ul>
              <li>Executive sponsorship and steering committee formation</li>
              <li>Critical data classification and residency mapping</li>
              <li>Vendor dependency assessment</li>
              <li>Compliance requirement documentation (GDPR, NIS2, etc.)</li>
            </ul>
          </div>
        </div>

      <?php elseif ($maturityLevel === 'Developing'): ?>
        <div class="action-priority maturity-developing">
          <h3><i class="fa-solid fa-arrow-trend-up"></i> Developing Level Actions</h3>
          <p>Build capabilities and implement controls across key domains:</p>
          <ul>
            <li><strong>Develop Strategy:</strong> Create a digital sovereignty roadmap aligned with business objectives</li>
            <li><strong>Implement Controls:</strong> Deploy encryption key management (BYOK/HYOK) and data residency controls</li>
            <li><strong>Establish Governance:</strong> Form sovereignty governance committee with clear responsibilities</li>
            <li><strong>Document Procedures:</strong> Create standard operating procedures for sovereignty-critical activities</li>
            <li><strong>Build Capabilities:</strong> Train technical teams on sovereign technologies and frameworks</li>
            <li><strong>Evaluate Solutions:</strong> Research open-source and sovereign-ready platforms</li>
          </ul>

          <div class="recommended-products">
            <h4>Key Focus Areas:</h4>
            <ul>
              <li>Data sovereignty and encryption controls</li>
              <li>Repeatable assessment processes</li>
              <li>Vendor risk management framework</li>
              <li>Compliance tracking and reporting</li>
            </ul>
          </div>
        </div>

      <?php elseif ($maturityLevel === 'Strategic'): ?>
        <div class="action-priority maturity-strategic">
          <h3><i class="fa-solid fa-chart-line"></i> Strategic Level Actions</h3>
          <p>Standardize processes and pursue organization-wide consistency:</p>
          <ul>
            <li><strong>Standardize Processes:</strong> Ensure sovereignty practices are consistent across all business units</li>
            <li><strong>Implement Standards:</strong> Adopt open standards and containerization for portability</li>
            <li><strong>Enhance Controls:</strong> Implement advanced monitoring, audit rights, and security log sovereignty</li>
            <li><strong>Build Resilience:</strong> Develop and test disaster recovery plans for geopolitical scenarios</li>
            <li><strong>Expand Open Source:</strong> Increase use of open-source software and contribute to strategic projects</li>
            <li><strong>Pursue Certifications:</strong> Obtain relevant certifications (NIS2, SecNumCloud, FedRAMP, etc.)</li>
          </ul>

          <div class="recommended-resources">
            <h4>Advancement Priorities:</h4>
            <ul>
              <li>Process standardization and documentation</li>
              <li>Cloud platform portability testing</li>
              <li>Organization-wide training programs</li>
              <li>Sovereignty metrics and KPIs definition</li>
            </ul>
          </div>
        </div>

      <?php else: ?>
        <div class="action-priority maturity-advanced">
          <h3><i class="fa-solid fa-shield-halved"></i> Advanced Level Actions</h3>
          <p>Drive innovation and thought leadership in digital sovereignty:</p>
          <ul>
            <li><strong>Drive Innovation:</strong> Pilot and deploy innovative sovereignty technologies and practices</li>
            <li><strong>Continuous Improvement:</strong> Continuously optimize processes and capabilities</li>
            <li><strong>Share Knowledge:</strong> Document and share best practices with industry and open-source communities</li>
            <li><strong>Lead Standards:</strong> Contribute to and influence digital sovereignty standards and frameworks</li>
            <li><strong>Expand Scope:</strong> Apply sovereignty principles to emerging technologies (AI, edge, quantum)</li>
            <li><strong>Stay Ahead:</strong> Proactively monitor and adapt to evolving regulations and geopolitical changes</li>
          </ul>

          <p class="note"><strong>Note:</strong> At the Advanced level, your focus shifts to driving innovation and thought leadership in digital sovereignty. Continue to measure, refine, and lead industry practices.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Detailed Domain Insights -->
    <div class="domain-insights">
      <h2><i class="fa-solid fa-list-check"></i> Detailed Domain Insights</h2>
      <p class="section-intro">Review your specific responses across all domains:</p>

      <?php foreach ($questions as $domainName => $domainData):
          $score = $domainScores[$domainName] ?? 0;
          $responses = $domainResponses[$domainName] ?? [];

          if ($score > 0):
      ?>
        <div class="domain-insight-card">
          <div class="domain-insight-header">
            <h3><?php echo htmlspecialchars($domainName); ?></h3>
            <span class="insight-score"><?php echo $score; ?>/<?php echo count($domainData['questions']); ?></span>
          </div>
          <p class="domain-insight-description"><?php echo htmlspecialchars($domainData['description']); ?></p>

          <div class="requirements-found">
            <h4>Requirements Identified:</h4>
            <ul>
              <?php foreach ($responses as $response): ?>
                <li><i class="fa-solid fa-check"></i> <?php echo htmlspecialchars($response); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php
          endif;
        endforeach;
      ?>

      <?php if ($totalScore === 0): ?>
        <div class="no-requirements">
          <p><i class="fa-solid fa-info-circle"></i> No Digital Sovereignty requirements were identified in this assessment. Consider focusing on other Red Hat value propositions.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Action Buttons -->
    <div class="form-actions no-print">
      <a href="generate-pdf.php" class="btn-primary">
        <i class="fa-solid fa-file-pdf"></i> Download PDF
      </a>
      <a href="index.php" class="btn-secondary">
        <i class="fa-solid fa-rotate-left"></i> New Assessment
      </a>
    </div>

    <!-- Footer -->
    <div class="results-footer">
      <p><small>Generated by Viewfinder Digital Sovereignty Readiness Assessment on <?php echo $assessmentDate; ?></small></p>
    </div>
  </div>
</body>
</html>
