<?php echo $this->getDoctype(); ?>
<html dir="ltr">
<head>
    <?php
    echo $this->setHead([
        'title' => [
            'content' => $this->getPageTitle()
        ],
        '{meta_0}' => [
            'attributes' => [
                'http-equiv' => 'Content-Type',
                'content' => 'text/html; charset=utf-8'
            ]
        ],
        '{meta_1}' => [
            'attributes' => [
                'http-equiv' => 'X-UA-Compatible',
                'content' => 'IE=Edge'
            ]
        ],
        '{meta_2}' => [
            'attributes' => [
                'name' => 'viewport',
                'content' => 'user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1'
            ]
        ],
        '{link_0}' => [
            'attributes' => [
                'rel' => 'stylesheet',
                'href' => "public/vendor/vendor.css?v=1673363766"
            ]
        ],
        '{link_1}' => [
            'attributes' => [
                'rel' => 'stylesheet',
                'href' => "public/style.css?v=" . time()
            ]
        ]
    ]);
    ?>
</head>

<body class="app-container bg-dark text-light" data-template="<?php echo $this->getName(); ?>">
    <?php echo (!empty($content) ? $content : ''); ?>


    <?php
    echo $this->setHead([
        '{script_0}' => [
            'attributes' => [
                'type' => 'text/javascript',
                'src' => "public/vendor/vendor.js?v=1673363766"
            ],
            'content' => ''
        ],
        '{script_2}' => [
            'attributes' => [
                'type' => 'text/javascript',
                'src' => "public/app.js?v=" . time()
            ],
            'content' => ''
        ]
    ]);
    ?>

    <script>
        jQuery.extend(true, app, {
            config: {
                settings: {
                    public_dir: "<?php echo $app->public_dir; ?>",
                    ajax_path: "<?php echo $app->ajax_path; ?>",
                }
            },
            phrase: {
                default_title: "<?php echo $this->getPageTitle(); ?>"
            }
        });
    </script>
</body>
</html>