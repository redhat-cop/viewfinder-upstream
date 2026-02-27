<!doctype html>
<html lang="en-us" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Viewfinder Lite</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/brands.css" />
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/tab-dark.css" />
  <link rel="stylesheet" href="css/patternfly.css" />
  <link rel="stylesheet" href="css/patternfly-addons.css" />

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #151515 !important;
      color: #ccc !important;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
    }

    .landing-page-wrapper {
      flex: 1 0 auto;
      min-height: calc(100vh - 200px);
      display: flex;
      flex-direction: column;
    }

    .landing-cards-grid {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin: 2rem 0;
    }

    .landing-cards-grid .landing-card {
      max-width: 800px;
      width: 100%;
    }

    .landing-card {
      background: #2a2a2a;
      border: 1px solid #444;
      border-radius: 8px;
      padding: 2rem;
      transition: all 0.3s ease;
    }

    .landing-card:hover {
      border-color: #0d60f8;
      box-shadow: 0 4px 16px rgba(13, 96, 248, 0.3);
      transform: translateY(-4px);
    }

    .landing-card-header {
      text-align: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #444;
    }

    .landing-card-header i {
      font-size: 3.5rem;
      color: #12bbd4;
      margin-bottom: 0.5rem;
      display: block;
    }

    .landing-card-header h2 {
      color: #9ec7fc;
      font-size: 1.75rem;
      margin: 0;
    }

    .landing-card-description {
      color: #ccc;
      line-height: 1.8;
      margin-bottom: 2rem;
      text-align: center;
      font-size: 1rem;
    }

    .landing-card-features {
      margin-bottom: 2rem;
    }

    .landing-card-features ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .landing-card-features li {
      padding: 0.75rem 0;
      padding-left: 2rem;
      position: relative;
      color: #ccc;
      line-height: 1.6;
    }

    .landing-card-features li:before {
      content: "✓";
      position: absolute;
      left: 0;
      color: #12bbd4;
      font-weight: bold;
      font-size: 1.2rem;
    }

    .landing-button {
      display: inline-block;
      padding: 1rem 2rem;
      border-radius: 4px;
      text-decoration: none;
      font-weight: 600;
      text-align: center;
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
      font-size: 1.1rem;
      width: 100%;
    }

    .landing-button i {
      margin-right: 0.5rem;
    }

    .landing-button-primary {
      background: linear-gradient(135deg, #0d60f8 0%, #004cbf 100%);
      color: #fff;
    }

    .landing-button-primary:hover {
      background: linear-gradient(135deg, #4d90fe 0%, #0d60f8 100%);
      box-shadow: 0 4px 12px rgba(13, 96, 248, 0.4);
      transform: translateY(-2px);
    }

    /* Disclaimer Footer Styles */
    .disclaimer-footer {
      flex-shrink: 0;
      background-color: #1a1a1a;
      border-top: 1px solid #444;
      padding: 1.5rem 2rem;
      text-align: center;
      margin-top: auto;
    }

    .disclaimer-footer p {
      color: #999;
      margin: 0;
      font-size: 0.9rem;
    }

    .disclaimer-footer strong {
      color: #ccc;
    }

    @media (max-width: 768px) {
      .landing-card {
        padding: 1.5rem;
      }

      .landing-card-header h2 {
        font-size: 1.5rem;
      }

      .landing-button {
        font-size: 1rem;
      }
    }
  </style>
</head>

<body>
  <div class="landing-page-wrapper">
    <div class="container" style="max-width: 1200px; margin: 2rem auto; padding: 4rem 2rem 0 2rem;">
      <div style="text-align: center; margin-bottom: 0;">
        <h1 style="color: #9ec7fc; font-size: 2rem; margin-bottom: 0; font-weight: 600;">
          Digital Sovereignty Navigator
        </h1>
      </div>

      <div class="landing-cards-grid">
        <!-- Digital Sovereignty Readiness Assessment Card -->
        <div class="landing-card">
          <div class="landing-card-header">
            <i class="fa-solid fa-clipboard-check"></i>
            <h2>Digital Sovereignty Readiness Assessment</h2>
          </div>
          <p class="landing-card-description">
            Quick 10-15 minute assessment to evaluate your organization's digital sovereignty readiness across 7 key domains
          </p>

          <div class="landing-card-features">
            <ul>
              <li><strong>21 Key Questions</strong> across 7 critical domains</li>
              <li><strong>Maturity Assessment</strong> with actionable recommendations</li>
              <li><strong>Instant Results</strong> with downloadable PDF report</li>
              <li><strong>Progress Auto-Save</strong> to resume anytime</li>
            </ul>
          </div>

          <a href="ds-qualifier/" class="landing-button landing-button-primary">
            <i class="fa-solid fa-rocket"></i> Start Assessment
          </a>
        </div>
      </div>
    </div>
  </div>

  <footer class="disclaimer-footer">
    <p><strong>Disclaimer:</strong> This Digital Sovereignty Readiness Assessment Tool is provided by Red Hat for informational purposes only to help organizations review their general sovereign posture. It cannot be used to validate an organization’s compliance with any specific sovereignty requirements. It is not endorsed by any regulatory authority, and its findings or recommendations do not constitute legal advice. Red Hat bears no legal responsibility or liability for the results or its use. No identity data will be collected or saved.</p>
  </footer>
</body>
</html>
