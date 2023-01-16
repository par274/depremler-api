<?php

namespace Init;

use PlatformRunDirect\Templater;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\DomCrawler\Crawler;

use GuzzleHttp\Client;

class app
{
    protected $request;
    protected $db;

    protected $query;
    protected $post;

    protected $serializer;

    protected $template;

    protected $tokens = [
        '90b8ae9fdc3c428586ec5a738f124a57',
        'b1e2e7689ae44dbe955ae04a6ada4004',
        'c7f84a864b0f431f970a4cdefedc6db'
    ];

    public function RunPlatform()
    {
        $this->request = new \PlatformRunDirect\Request();

        $this->query = new \PlatformRunDirect\Get();
        $this->post = new \PlatformRunDirect\Post();

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $this->serializer = new Serializer($normalizers, $encoders);

        $this->template = new Templater();
        $this->templaterController();
    }

    public function RenderPlatformDirect()
    {
        if ($this->request->getRequestMethod() == "GET")
        {
            $client = new Client([
                'http_errors' => false,
                'timeout' => 3,
                'connect_timeout' => 3.14
            ]);

            $response = $client->request('GET', 'http://www.koeri.boun.edu.tr/scripts/lst4.asp');

            if ($response->getStatusCode() == 200)
            {
                $content = $response->getBody()->getContents();
            }
            else
            {
                if ($this->query->has('xml'))
                {
                    $this->renderStream(
                        $this->xmlSerialize([
                            'status' => 'fail-response-error',
                            'message' => 'The connection to the site could not be established.'
                        ]),
                        'application/xml'
                    );
                }
                else
                {
                    $this->renderStream(
                        $this->jsonSerialize([
                            'status' => 'fail-response-error',
                            'message' => 'The connection to the site could not be established.'
                        ])
                    );
                }

                return false;
            }

            $output = [
                'status' => 'ok',
                'data' => $this->getData($content)
            ];

            if ($this->query->has('json'))
            {
                $data = $this->jsonSerialize($output);
                $this->renderStream($data);
            }
            else if ($this->query->has('xml'))
            {
                $data = $this->xmlSerialize($output);
                $this->renderStream($data, 'application/xml');
            }
            else
            {
                $data = [
                    'data' => [
                        'json' => $this->jsonSerialize($output)
                    ]
                ];
                echo $this->template->render('{page_container:table}', $data);
            }
        }
        elseif ($this->request->getRequestMethod() == "POST")
        {
        }
    }

    protected function getData($html)
    {
        $crawler = new Crawler($html);
        $rawData = $crawler->filter('pre')->html();

        $lines = explode("\r\n", $rawData);
        //$lines = array_slice($lines, 0, 200);

        foreach ($lines as $item)
        {
            $parts = explode('  ', $item);
            $parts = \array_filter($parts, function ($line)
            {
                if (strlen($line) > 1)
                {
                    $line = \strip_tags($line);
                    $line = \trim($line);

                    return $line;
                }
            });

            if (count($parts) > 1)
            {
                $lineItems[] = \array_values($parts);
            }
        }

        foreach (\array_slice($lineItems, 2) as $row)
        {
            $dateTime = \strtotime(
                \str_replace('.', '-', $row[0])
            );

            $revisionDateTime = null;
            if (isset($row[9]))
            {
                $revisionDateTime = \strtotime(
                    \str_replace(['.', '(', ')'], ['-', '', ''], $row[9])
                );
                $revisionDateTime = date('d-m-Y H:i', $revisionDateTime);
            }

            if (!isset($row[8]))
            {
                $row[8] = '';
            }

            $ll = \sprintf('%s,%s', \trim($row[1]), \trim($row[2]));

            $data[] = [
                'date' => [
                    'humanreadable' => (new \PlatformRunDirect\DateTime())->getFullDateTime($dateTime),
                    'date' => date('d-m-Y', $dateTime),
                    'time' => date('H:i', $dateTime)
                ],
                'geo' => [
                    'full' => $ll,
                    'latitude' => \trim($row[1]),
                    'longitude' => \trim($row[2]),
                    'google_maps' => \htmlspecialchars("https://www.google.com/maps?q={$ll}&ll={$ll}&z=12")
                ],
                'depth' => \trim($row[3]),
                'ml' => $row[5],
                'location' => [
                    'full' => \trim(
                        \ucwords(\strtolower($row[7]))
                    ),
                    'city' => \trim(
                        \ucwords(
                            \strtolower(preg_replace('/([a-zA-Z- ]+)(?:\s)?\(([a-zA-Z ]+)\)/', '$1', $row[7]))
                        )
                    ),
                    'state' => \trim(
                        \ucwords(
                            \strtolower(preg_replace('/([a-zA-Z- ]+)(?:\s)?\(([a-zA-Z ]+)\)/', '$2', $row[7]))
                        )
                    )
                ],
                'accuracy' => (isset($row[9])) ? \trim("{$row[8]} ({$revisionDateTime})") : \trim($row[8])
            ];
        }

        return $data;
    }

    protected function controlToken()
    {
        if ($this->query->has('token'))
        {
            if (in_array($this->query->get('token'), $this->tokens))
            {
                return true;
            }

            return [
                'status' => 'fail-token-not-confirmed',
                'message' => 'Token not confirmed.'
            ];
        }

        return [
            'status' => 'fail-token-missing',
            'message' => 'Token is missing.'
        ];
    }

    protected function streamableRandomFile(): string
    {
        $v4 = \PlatformRunDirect\Uuid::v4();

        return "IGS_STREAMABLE_PACKAGE_PLATFORM_{$v4}.miniature";
    }

    protected function jsonSerialize(array $data)
    {
        return $this->serializer->serialize(
            $data,
            'json',
            ['json_encode_options' => \JSON_PRESERVE_ZERO_FRACTION]
        );
    }

    protected function xmlSerialize(array $data)
    {
        return (new XmlEncoder())->encode(
            $data,
            'xml',
            [
                'xml_encoding' => 'utf-8',
                XmlEncoder::ENCODER_IGNORED_NODE_TYPES => [\XML_COMMENT_NODE]
            ]
        );
    }

    protected function renderStream($data, string $type = 'application/json')
    {
        return $this->request->setContentDispositionStreamed(
            $this->streamableRandomFile(),
            $data,
            $type
        );
    }

    private function templaterController()
    {
        $this->template->addGlobals([
            'app' => [
                'uri' => \PlatformRunDirect\AppSub::getFullUrl(),
                'public_dir' => \PlatformRunDirect\AppSub::getPublicDir(),
                'ajax_path' => $this->getAjaxPath(),
                'request' => $this->request,
                'post' => $this->post,
                'query' => $this->query,
                'serializer' => $this->serializer
            ]
        ]);
    }

    public function getAjaxPath()
    {
        if (\PlatformRunDirect\AppSub::getSubDir())
        {
            return "/" . \PlatformRunDirect\AppSub::getSubDir();
        }

        return '';
    }
}
