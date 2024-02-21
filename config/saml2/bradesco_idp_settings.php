<?php

return $settings = [

    /*****
     * One Login Settings
     */

    // If 'strict' is True, then the PHP Toolkit will reject unsigned
    // or unencrypted messages if it expects them signed or encrypted
    // Also will reject the messages if not strictly follow the SAML
    // standard: Destination, NameId, Conditions ... are validated too.
    'strict' => true, //@todo: make this depend on laravel config

    // Enable debug mode (to print errors)
    'debug' => env('APP_DEBUG', false),

    // Service Provider Data that we are deploying
    'sp' => [

        // Specifies constraints on the name identifier to be used to
        // represent the requested subject.
        // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',

        // Usually x509cert and privateKey of the SP are provided by files placed at
        // the certs folder. But we can also provide them with the following parameters
        'x509cert' => 'MIIFDDCCAvQCCQCbpYztkOfQfjANBgkqhkiG9w0BAQsFADBIMQswCQYDVQQGEwJCUjESMBAGA1UECAwJU2FvIFBhdWxvMRIwEAYDVQQHDAlTYW8gUGF1bG8xETAPBgNVBAoMCFZhbGlkSFVCMB4XDTIyMTExNzIyNTYyMVoXDTMyMTExNDIyNTYyMlowSDELMAkGA1UEBhMCQlIxEjAQBgNVBAgMCVNhbyBQYXVsbzESMBAGA1UEBwwJU2FvIFBhdWxvMREwDwYDVQQKDAhWYWxpZEhVQjCCAiIwDQYJKoZIhvcNAQEBBQADggIPADCCAgoCggIBANT213EIHvD/soexeQpS7f1RrjyScj/t0lC5xRCM7BfKAZ5ic8HmJ+LLVKRq4ER+CtlwxOY3TTjPQpq9OWlHpVktpXmzqcWarrKRFpu6usepAzllMwRnNvA+NhVp77mOwY33Jh+KBmachOer9H0jMiBC9eVhpRPWeCHOnY/LemQgSgbEeEM+FL/MhJR1aPF8I/xJqygTQW3lukxfrgGk8K/LY2pf0/9R4Bzs98nmIMre5iGs9k99iNIk/hNme2ByhgOhQi61A0polOgjsThcMdwTTFvZSnwimaJdZnX8auqRyJFN2JBLXHMSU36QB5pM5ojelN3KfOeR1K0eoMA/UF7SucexQ7vsPF2j5MHfk+vxcqxua4FFjpqrpebqX/6AUOf2KOKCMIrvPdDZbdxy8XZ7ojKsEldbamf5RMyBy62P9EnKA3V9nepRjXBlJpu1QxiuUEvlqYeBRamnZXGGeaptBZh4aEt8dt9+kpEc5JXt/445ekSAb9eHJpAfzH4pWxgYxvnW/5JxdTwa/qCURND7XHLf+HwiQLb5aNy7ZEy6f+0QCwFj8yl5EJHZ+4ilXersLkqkW4APYOC3jpxB4Y991qZ6A4fW7Cit+At4Xr482xseoEmSpC1t9ISwpNhEr86e5F4GcfFBdOOqm/k7tizC1Ao4yVfJ0w+sPkB048rbAgMBAAEwDQYJKoZIhvcNAQELBQADggIBAJojG65DW1nYtUVNAGuZnjxYfykVgk7Vytid7agwb9GoGPqBJrB2VrzfRGOh9ceAvh5c0ia8lGBG/VHlAhDz29lVfdp//Kz2k0ze5mLhN3JQRZYbvUFTbxftKSmiqr79y4eAGwwSJFD9b3hvRNzsPBKThDSpF9rVDSx92nC6tEsXuV/x9gOasQ957xZOSpr/9/YbYS3mj1HvqvTyZJyojL/u/NjiJpXegW1p7Clb5hy9DJxKKv91DViRrwZkjF/iy1aIRPN6gp5R26fY7OB+xBpRiDjJJyJR1HIfpi3p/Jn2MX4WcHGsEcLCRAvKHM6x6vdEwaLtrfYVEwCq7ru/xpPcGsfFI3R2Ubj6ApCfwvF+Wi/ihFFEhZR/z37wn6I466jRMFG9b6ruWUuoiXTH72i+7MKmFhLJVgrdmCIfL5SHD4t3heEkAqewHMuBq74lUc3lp/QkO40juz/44C7k6EIu6S09MwPdEMxL1GOu6I+j0WAxE/0aUrBE5i1zTlHzbhPM704d0HB8TXZOFghAl3B1BUKB/pu0MFhbXUj7yWn1BTyTuYSLp9FQQMkpwnX6U4nQUrfmQR8mrBTnbhpSRlI4uZ9c+qUzVyodadiQx0FgDH0aCxTub6JhxbfrKvBcaljjNZ5KjIkf2NskC3C7iPCM9BpLODfHoxzJbnOL7Xse',
        'privateKey' => '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',

        // Identifier (URI) of the SP entity.
        // Leave blank to use the '{idpName}_metadata' route, e.g. 'test_metadata'.
        'entityId' => '',

        // Specifies info about where and how the <AuthnResponse> message MUST be
        // returned to the requester, in this case our SP.
        'assertionConsumerService' => [
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-POST binding.
            // Leave blank to use the '{idpName}_acs' route, e.g. 'test_acs'
            'url' => '',
        ],
        
        'attributeConsumingService' => [
            'serviceName' => 'SP Bradesco',
            'requestedAttributes' => [
                [
                    'name' => 'First Name',
                    'isRequired' => true
                ],
                [
                    'name' => 'Last Name',
                    'isRequired' => true
                ]
            ]
        ],

        // Specifies info about where and how the <Logout Response> message MUST be
        // returned to the requester, in this case our SP.
        // Remove this part to not include any URL Location in the metadata.
        'singleLogoutService' => [
            // URL Location where the <Response> from the IdP will be returned,
            // using HTTP-Redirect binding.
            // Leave blank to use the '{idpName}_sls' route, e.g. 'test_sls'
            'url' => '',
        ],
    ],

    // Identity Provider Data that we want connect with our SP
    'idp' => [
        // Identifier of the IdP entity  (must be a URI)
        'entityId' => "https://sts.bradesco.com.br/federationmetadata/2007-06/federationmetadata.xml",
        // SSO endpoint info of the IdP. (Authentication Request protocol)
        'singleSignOnService' => [
            // URL Target of the IdP where the SP will send the Authentication Request Message,
            // using HTTP-Redirect binding.
            'url' => "https://sts.bradesco.com.br/adfs/ls/",
        ],
        // SLO endpoint info of the IdP.
        'singleLogoutService' => [
            // URL Location of the IdP where the SP will send the SLO Request,
            // using HTTP-Redirect binding.
            'url' => "https://sts.bradesco.com.br/adfs/ls/",
        ],
        // Public x509 certificate of the IdP
        'x509cert' => "MIIC4jCCAcqgAwIBAgIQGriOknHF1ZdOqoV9AcR2tjANBgkqhkiG9w0BAQsFADAtMSswKQYDVQQDEyJBREZTIFNpZ25pbmcgLSBzdHMuYnJhZGVzY28uY29tLmJyMB4XDTIxMTExMzE5Mzk0NFoXDTIzMTExMzE5Mzk0NFowLTErMCkGA1UEAxMiQURGUyBTaWduaW5nIC0gc3RzLmJyYWRlc2NvLmNvbS5icjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBANfGBNiyLA77ME099SC4SdlbFewVrHphuxJqiodn61C2phqcp1nEH9pBNqY9uU9Tci+fDZDbR90ECYQWrnP0KRwMBTUb2+liQa7p6gfYkNVHLKdscxt8J0T+v1xF/FvSpQafkSWnJMazaEk0y/UGTP3HwSiKPVxERM18pbXfUftGkP8EGaxuCsGS8ozSPc8ccl1iyXfosV8ZClXszKtkBDsRf2zTHIsowzhH40Ol7A6sUOM4Er2Uf11+w/ZNE3RZnjgiNkHFjlXIF3pCp/j/Yeja5D3pYsdsP4FKyP8zlUO2tjTLd4NjhokP906g5oH7M1oaObwAdsoZv0aTJYXblHUCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAaLLh/9PuH45RUmwyuMxaloTYoEGP36UXrbnY63c4quIbwVDhB+/HC+i4cBzJR1Ago/oAFq4trL4fNNAfA0ejg6VOqT3DjHYB6Cq8QeUAi6Vp2vQ7l7X+CI6s3n3FIfevBb5p8UT6wM/b2gNlhU9dRxsFyl1x0n4lMlBF08VDlLQhvjzn2zriHt1ueaPgHA4VCufKY5zAzNzRQr+uBgUqjymEMaTnB7cUhAgVbugjgTkeWYc5v9r7bCYrRzt2NqkLa2mZkNyBkWdY3kwfLlnzqnd1hfe6YHLjA8ghZ5WH36/Nx8IU2NcVdJBVP9SrzFMoY0px0pFonBZku3rcTOA6xg==",
        /*
         *  Instead of use the whole x509cert you can use a fingerprint
         *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it)
         */
        // 'certFingerprint' => '',
    ],



    /***
     *
     *  OneLogin advanced settings
     *
     *
     */
    // Security settings
    'security' => [

        /** signatures and encryptions offered */

        // Indicates that the nameID of the <samlp:logoutRequest> sent by this SP
        // will be encrypted.
        'nameIdEncrypted' => false,

        // Indicates whether the <samlp:AuthnRequest> messages sent by this SP
        // will be signed.              [The Metadata of the SP will offer this info]
        'authnRequestsSigned' => false,

        // Indicates whether the <samlp:logoutRequest> messages sent by this SP
        // will be signed.
        'logoutRequestSigned' => false,

        // Indicates whether the <samlp:logoutResponse> messages sent by this SP
        // will be signed.
        'logoutResponseSigned' => false,

        /* Sign the Metadata
         False || True (use sp certs) || array (
                                                    keyFileName => 'metadata.key',
                                                    certFileName => 'metadata.crt'
                                                )
        */
        'signMetadata' => false,


        /** signatures and encryptions required **/

        // Indicates a requirement for the <samlp:Response>, <samlp:LogoutRequest> and
        // <samlp:LogoutResponse> elements received by this SP to be signed.
        'wantMessagesSigned' => false,

        // Indicates a requirement for the <saml:Assertion> elements received by
        // this SP to be signed.        [The Metadata of the SP will offer this info]
        'wantAssertionsSigned' => true,

        // Indicates a requirement for the NameID received by
        // this SP to be encrypted.
        'wantNameIdEncrypted' => false,

        // Authentication context.
        // Set to false and no AuthContext will be sent in the AuthNRequest,
        // Set true or don't present thi parameter and you will get an AuthContext 'exact' 'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport'
        // Set an array with the possible auth context values: array ('urn:oasis:names:tc:SAML:2.0:ac:classes:Password', 'urn:oasis:names:tc:SAML:2.0:ac:classes:X509'),
        'requestedAuthnContext' => true,
    ],

    // Contact information template, it is recommended to suply a technical and support contacts
    'contactPerson' => [],

    // Organization information template, the info in en_US lang is recomended, add more if required
    'organization' => [
        'en-US' => [
            'name' => 'validhub',
            'displayname' => 'ValidHub',
            'url' => 'https://regdoc.com.br/'
        ],
        'pt-BR' => [
            'name' => 'validhub',
            'displayname' => 'ValidHub',
            'url' => 'https://regdoc.com.br/'
        ]
    ],

/* Interoperable SAML 2.0 Web Browser SSO Profile [saml2int]   http://saml2int.org/profile/current

   'authnRequestsSigned' => false,    // SP SHOULD NOT sign the <samlp:AuthnRequest>,
                                      // MUST NOT assume that the IdP validates the sign
   'wantAssertionsSigned' => true,
   'wantAssertionsEncrypted' => true, // MUST be enabled if SSL/HTTPs is disabled
   'wantNameIdEncrypted' => false,
*/

];
