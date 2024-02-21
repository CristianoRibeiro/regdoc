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
        'x509cert' => '',
        'privateKey' => '',

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
            'serviceName' => 'SP Teste',
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
        'entityId' => "https://app.onelogin.com/saml/metadata/35184139-1e86-46fb-b66d-eb106c21d490",
        // SSO endpoint info of the IdP. (Authentication Request protocol)
        'singleSignOnService' => [
            // URL Target of the IdP where the SP will send the Authentication Request Message,
            // using HTTP-Redirect binding.
            'url' => "https://validhub.onelogin.com/trust/saml2/http-post/sso/35184139-1e86-46fb-b66d-eb106c21d490",
        ],
        // SLO endpoint info of the IdP.
        'singleLogoutService' => [
            // URL Location of the IdP where the SP will send the SLO Request,
            // using HTTP-Redirect binding.
            'url' => "https://validhub.onelogin.com/trust/saml2/http-redirect/slo/1894711",
        ],
        // Public x509 certificate of the IdP
        'x509cert' => "MIID3zCCAsegAwIBAgIUHu9Mnp3c22K3sNdLng7cwJro2zMwDQYJKoZIhvcNAQEFBQAwRjERMA8GA1UECgwIVmFsaWQgU0ExFTATBgNVBAsMDE9uZUxvZ2luIElkUDEaMBgGA1UEAwwRT25lTG9naW4gQWNjb3VudCAwHhcNMjIxMDI1MTcxNDE2WhcNMjcxMDI1MTcxNDE2WjBGMREwDwYDVQQKDAhWYWxpZCBTQTEVMBMGA1UECwwMT25lTG9naW4gSWRQMRowGAYDVQQDDBFPbmVMb2dpbiBBY2NvdW50IDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALRzavWtp0yvoQusFn+8tzXK+JxeWeB6ulbCc2GJqgJKeENEVxBk2fcsp1r4wqyJ3heuNhKlYz9SAjt2XSZjYo3VXydOTs0yNfLvWtyZ+rc0hZRKrbQU/7RkKIBm8OIQi63aSBmC5Kkig3lIoFIIWWfoBVlMVgeV+BsKnPHMFql7HAdESkSKZefs7WLib/C8KJ3Q0EbT4YDkBJoo50slgAJMOCphHFmbhCC41ldji8jdHmXfiRG0HvrRSdQ5DIV9wRFpea7GkWAkEUhbhQBwq2ecJzeAM4cHJLwAYrzIjbfb6eSNBMTrKxwJ2yWJDReEZRk8MXKp+ECylp34/VtIA9UCAwEAAaOBxDCBwTAMBgNVHRMBAf8EAjAAMB0GA1UdDgQWBBSIg8jHd0Nn2jmP36wgGf7zO9lTnTCBgQYDVR0jBHoweIAUiIPIx3dDZ9o5j9+sIBn+8zvZU52hSqRIMEYxETAPBgNVBAoMCFZhbGlkIFNBMRUwEwYDVQQLDAxPbmVMb2dpbiBJZFAxGjAYBgNVBAMMEU9uZUxvZ2luIEFjY291bnQgghQe70yendzbYrew10ueDtzAmujbMzAOBgNVHQ8BAf8EBAMCB4AwDQYJKoZIhvcNAQEFBQADggEBAGoSUzeyw49B25ulle9f70fH6oaEYoTWxXvJGUzmZ2tg11h9JtxTDjEa6QrfTdDWDALGn3KFH96MaNKIJty0PcZvYIJAZ6wYRBEsYtgLUtNrT9TB5pu/vFLubexRuVmNu2mb28a/byRl3g6gCHajsPIJ8YT5n9nn2W0Y8e/zGuXsFdYG80iVMhBN0MmuMYcPWPCDpU/cOwwrmK59xJ7kQiSMqkUP80PHkdgGHRZCo2aKinm2o8kyKjOGKEg+GxUcIBlk2vB3pVNC1+J3eQC/921MT5kdpjpq5aw643mcYTbwuNfN+hm+QqNaooz8M2Bj1ZjReTQsLRHk/ZkLB3feGPw=",
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
        'wantAssertionsSigned' => false,

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
