<?php
/**
 * Digital Sovereignty Readiness Assessment - Weighting Profiles
 *
 * Defines industry/context-specific weighting profiles for domain scoring
 * Weights: 1.0 = standard, 1.5 = higher priority, 2.0 = critical
 */

return [
    'balanced' => [
        'name' => 'Balanced',
        'description' => 'Equal weighting across all domains - suitable for general assessments and organizations without specific regulatory constraints.',
        'icon' => 'fa-balance-scale',
        'weights' => [
            'Data Sovereignty' => 1.0,
            'Technical Sovereignty' => 1.0,
            'Operational Sovereignty' => 1.0,
            'Assurance Sovereignty' => 1.0,
            'Open Source' => 1.0,
            'Executive Oversight' => 1.0,
            'Managed Services' => 1.0
        ]
    ],

    'financial' => [
        'name' => 'Financial Services',
        'description' => 'Emphasizes data protection, audit controls, and compliance for banking and finance (PCI DSS, data residency, anti-money laundering).',
        'icon' => 'fa-building-columns',
        'weights' => [
            'Data Sovereignty' => 2.0,      // Critical: PCI DSS, data residency
            'Technical Sovereignty' => 1.0,
            'Operational Sovereignty' => 1.5, // Important: Business continuity
            'Assurance Sovereignty' => 2.0,   // Critical: Audit requirements
            'Open Source' => 1.0,
            'Executive Oversight' => 1.5,     // Important: Governance
            'Managed Services' => 1.5         // Important: Third-party risk
        ]
    ],

    'healthcare' => [
        'name' => 'Healthcare',
        'description' => 'Focuses on patient data protection (HIPAA, GDPR) and operational resilience for life-critical systems requiring 24/7 availability.',
        'icon' => 'fa-heart-pulse',
        'weights' => [
            'Data Sovereignty' => 2.0,        // Critical: HIPAA, patient data
            'Technical Sovereignty' => 1.0,
            'Operational Sovereignty' => 2.0, // Critical: Patient safety
            'Assurance Sovereignty' => 1.5,   // Important: Compliance
            'Open Source' => 1.0,
            'Executive Oversight' => 1.5,
            'Managed Services' => 1.5
        ]
    ],

    'government' => [
        'name' => 'Government & Public Sector',
        'description' => 'Comprehensive sovereignty for public sector organizations handling sensitive citizen data and critical national infrastructure (NIS2, FedRAMP).',
        'icon' => 'fa-landmark',
        'weights' => [
            'Data Sovereignty' => 2.0,        // Critical: Citizen data
            'Technical Sovereignty' => 1.5,   // Important: Independence
            'Operational Sovereignty' => 1.5, // Important: Continuity
            'Assurance Sovereignty' => 2.0,   // Critical: National security
            'Open Source' => 1.5,             // Important: Transparency
            'Executive Oversight' => 2.0,     // Critical: Accountability
            'Managed Services' => 1.5         // Important: Control
        ]
    ],

    'technology' => [
        'name' => 'Technology & SaaS',
        'description' => 'Prioritizes technical independence, open source strategy, and multi-cloud portability to avoid vendor lock-in and maintain competitive agility.',
        'icon' => 'fa-laptop-code',
        'weights' => [
            'Data Sovereignty' => 1.5,
            'Technical Sovereignty' => 2.0,   // Critical: Vendor lock-in
            'Operational Sovereignty' => 1.5, // Important: Scalability
            'Assurance Sovereignty' => 1.0,
            'Open Source' => 2.0,             // Critical: Innovation
            'Executive Oversight' => 1.0,
            'Managed Services' => 1.5         // Important: Multi-cloud
        ]
    ],

    'manufacturing' => [
        'name' => 'Manufacturing & Industrial',
        'description' => 'Emphasizes operational resilience, production uptime, and OT/IT integration for continuous operations and IP protection in industrial control systems.',
        'icon' => 'fa-industry',
        'weights' => [
            'Data Sovereignty' => 1.5,        // Important: IP protection
            'Technical Sovereignty' => 1.0,
            'Operational Sovereignty' => 2.0, // Critical: Production uptime
            'Assurance Sovereignty' => 1.5,   // Important: Quality systems
            'Open Source' => 1.0,
            'Executive Oversight' => 1.5,
            'Managed Services' => 2.0         // Critical: OT/IT integration
        ]
    ],

    'telecommunications' => [
        'name' => 'Telecommunications',
        'description' => 'Focuses on critical infrastructure protection, subscriber data sovereignty, and 24/7 service availability (NIS2, network security).',
        'icon' => 'fa-tower-cell',
        'weights' => [
            'Data Sovereignty' => 2.0,        // Critical: Subscriber data
            'Technical Sovereignty' => 1.5,   // Important: Network independence
            'Operational Sovereignty' => 2.0, // Critical: Service availability
            'Assurance Sovereignty' => 2.0,   // Critical: NIS2, telecoms regulations
            'Open Source' => 1.0,
            'Executive Oversight' => 1.5,
            'Managed Services' => 1.5
        ]
    ],

    'energy' => [
        'name' => 'Energy & Utilities',
        'description' => 'Prioritizes critical infrastructure protection, grid reliability, and SCADA system security for essential services (NIS2, NERC CIP).',
        'icon' => 'fa-bolt',
        'weights' => [
            'Data Sovereignty' => 1.5,
            'Technical Sovereignty' => 1.5,
            'Operational Sovereignty' => 2.0, // Critical: Grid reliability
            'Assurance Sovereignty' => 2.0,   // Critical: Critical infrastructure
            'Open Source' => 1.0,
            'Executive Oversight' => 1.5,
            'Managed Services' => 1.5
        ]
    ],

    'custom' => [
        'name' => 'Custom',
        'description' => 'Define your own domain weightings based on your specific regulatory requirements, business model, and organizational priorities.',
        'icon' => 'fa-sliders',
        'weights' => [
            'Data Sovereignty' => 1.0,
            'Technical Sovereignty' => 1.0,
            'Operational Sovereignty' => 1.0,
            'Assurance Sovereignty' => 1.0,
            'Open Source' => 1.0,
            'Executive Oversight' => 1.0,
            'Managed Services' => 1.0
        ]
    ]
];
