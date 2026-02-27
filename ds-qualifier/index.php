<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Digital Sovereignty Readiness Assessment - Viewfinder</title>

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
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
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
  </style>
</head>

<body>
  <header class="pf-c-page__header">
    <div class="pf-c-page__header-brand">
      <div class="pf-c-page__header-brand-toggle"></div>
    </div>

    <div class="widget">
      <a href="../index.php"><button><i class="fa-solid fa-home"></i> Home</button></a>
    </div>
  </header>

  <div class="container">
    <?php
    // Load questions configuration
    $questions = require_once 'config.php';
    ?>

    <div class="qualifier-header">
      <h1><i class="fa-solid fa-clipboard-check"></i> Digital Sovereignty Readiness Assessment</h1>
      <p class="subtitle">Quick 10-15 minute assessment to evaluate digital sovereignty readiness</p>
    </div>

    <div class="qualifier-intro" id="intro-section">
      <h3><i class="fa-solid fa-info-circle"></i> About This Tool</h3>
      <p>This lightweight assessment tool helps evaluate your organization's digital sovereignty readiness.
         Answer the questions below based on your current practices and requirements.</p>
      <ul>
        <li><strong>Time Required:</strong> 10-15 minutes</li>
        <li><strong>Questions:</strong> 21 questions across 7 domains (Yes / No / Don't Know)</li>
        <li><strong>Output:</strong> Readiness score with recommended next steps</li>
        <li><strong>Don't Know?</strong> Questions marked "Don't Know" will appear as "Questions to Research"</li>
      </ul>
    </div>

    <form action="results.php" method="POST" id="qualifier-form">
      <!-- Domain Questions -->
      <?php
      $sectionIndex = 0;
      foreach ($questions as $domainName => $domainData):
        $sectionIndex++;
      ?>
        <div class="domain-section section-pane"
             id="domain-<?php echo strtolower(str_replace(' ', '-', $domainName)); ?>"
             data-section="<?php echo $sectionIndex; ?>"
             style="display: <?php echo $sectionIndex === 1 ? 'block' : 'none'; ?>;">
          <div class="domain-header">
            <h2><i class="fa-solid fa-shield-halved"></i> <?php echo htmlspecialchars($domainName); ?></h2>
            <p class="domain-description"><?php echo htmlspecialchars($domainData['description']); ?></p>
          </div>

          <div class="questions-list">
            <?php foreach ($domainData['questions'] as $question): ?>
              <div class="question-item">
                <div class="question-header">
                  <span class="question-text">
                    <?php echo htmlspecialchars($question['text']); ?>
                    <?php if (!empty($question['tooltip'])): ?>
                      <span class="tooltip-icon" data-tooltip="<?php echo htmlspecialchars($question['tooltip']); ?>">
                        <i class="fa-solid fa-circle-info"></i>
                      </span>
                    <?php endif; ?>
                  </span>
                </div>
                <div class="button-group" data-domain="<?php echo $domainData['domain_key']; ?>">
                  <input type="radio"
                         id="<?php echo $question['id']; ?>-yes"
                         name="<?php echo $question['id']; ?>"
                         value="<?php echo $question['weight']; ?>"
                         class="question-radio">
                  <label for="<?php echo $question['id']; ?>-yes" class="btn-option btn-yes">
                    <i class="fa-solid fa-check"></i> Yes
                  </label>

                  <input type="radio"
                         id="<?php echo $question['id']; ?>-no"
                         name="<?php echo $question['id']; ?>"
                         value="0"
                         class="question-radio">
                  <label for="<?php echo $question['id']; ?>-no" class="btn-option btn-no">
                    <i class="fa-solid fa-xmark"></i> No
                  </label>

                  <input type="radio"
                         id="<?php echo $question['id']; ?>-unknown"
                         name="<?php echo $question['id']; ?>"
                         value="unknown"
                         class="question-radio">
                  <label for="<?php echo $question['id']; ?>-unknown" class="btn-option btn-unknown">
                    <i class="fa-solid fa-question"></i> Don't Know
                  </label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <!-- Navigation Buttons -->
      <div class="form-navigation">
        <button type="button" id="prev-section" class="btn-secondary nav-button" style="display: none;">
          <i class="fa-solid fa-arrow-left"></i> Previous
        </button>
        <button type="button" id="next-section" class="btn-primary nav-button">
          Next <i class="fa-solid fa-arrow-right"></i>
        </button>
        <button type="submit" id="submit-form" class="btn-success nav-button" style="display: none;">
          <i class="fa-solid fa-chart-line"></i> Generate Qualification Report
        </button>
      </div>

      <!-- Reset Button -->
      <div class="form-reset">
        <button type="reset" class="btn-secondary btn-reset">
          <i class="fa-solid fa-rotate-left"></i> Reset All Answers
        </button>
      </div>
    </form>
  </div>

  <!-- Load DS Qualifier JavaScript -->
  <script src="js/ds-qualifier.js"></script>
</body>
</html>
