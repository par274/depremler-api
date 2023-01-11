<?php $this->setPageTitle('Depremler Tablo'); ?>
<?php $json = $app->serializer->decode($data['json'], 'json'); ?>
<div class="app-introduction">
    <div>
        <small>
            <span>Source from: </span>
            <a target="_blank" class="text-white" href="http://www.koeri.boun.edu.tr">
                <u>http://www.koeri.boun.edu.tr</u>
            </a>
        </small>
    </div>
    <div>
        <small>
            <span>If you want to see this page as Json/XML follow the links: </span>
            <ul class="mb-0">
                <li>
                    <a class="text-white" href="<?php echo $app->uri; ?>?json">
                        <u><?php echo $app->uri; ?>?json</u>
                    </a>
                </li>
                <li>
                    <a class="text-white" href="<?php echo $app->uri; ?>?xml">
                        <u><?php echo $app->uri; ?>?xml</u>
                    </a>
                </li>
            </ul>
        </small>
    </div>
</div>
<hr>
<div class="app-table mb-3">
    <table class="table table-striped table-bordered table-dark mb-0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Tarih</th>
                <th scope="col">Derinlik(km)</th>
                <th scope="col">Şiddet</th>
                <th scope="col">Bölge/Eyalet</th>
                <th scope="col">Şehir</th>
                <th scole="col">Doğruluk</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($json['data'] as $item) :
            ?>
                <?php
                if ($item['ml'] >= 4.5)
                {
                    $tableClass = " class=\"table-warning text-dark\"";
                }
                else if ($item['ml'] >= 6.5)
                {
                    $tableClass = " class=\"table-danger text-dark\"";
                }
                else
                {
                    $tableClass = '';
                }
                ?>
                <tr<?php echo $tableClass; ?>>
                    <td><?php echo $i++ ?></td>
                    <td><?php echo $item['date']['humanreadable']; ?></td>
                    <td><?php echo $item['depth']; ?></td>
                    <td><?php echo $item['ml']; ?></td>
                    <td><?php echo $item['location']['state']; ?></td>
                    <td><?php echo $item['location']['city']; ?></td>
                    <td><?php echo $item['accuracy']; ?></td>
                    </tr>
                <?php endforeach; ?>
        </tbody>
    </table>
</div>
<hr>
<div class="app-end d-flex flex-lg-row flex-column text-light mb-3">
    <div>
        <div>
            <small>
                Copyright © <?php echo date('Y', time()); ?>
                <a target="_blank" class="text-white" href="https://github.com/par274">Par274</a>
                /
                <a target="_blank" class="text-white" href="https://www.r10.net/profil/90047-scarecrow.html">R10 Scarecrow</a>
            </small>
        </div>
        <div>
            <small>You can use it in your projects.</small>
        </div>
    </div>
    <div class="ml-lg-auto mt-lg-0 mt-3">
        <small>
            <span>Packages used in this project:</span>
            <div class="d-flex">
                <div>
                    <ul class="mb-0 pl-2">
                        <li>
                            <a class="text-white" href="https://github.com/guzzle/guzzle">
                                Guzzle/Guzzle
                            </a>
                        </li>
                        <li>
                            <a class="text-white" href="https://symfony.com/doc/5.4/components/serializer.html">
                                Symfony/Serializer
                            </a>
                        </li>
                        <li>
                            <a class="text-white" href="https://symfony.com/doc/5.4/components/dom_crawler.html">
                                Symfony/DomCrawler
                            </a>
                        </li>
                        <li>
                            <a class="text-white" href="https://carbon.nesbot.com/">
                                Nesbot/Carbon
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <ul class="mb-0 pl-5">
                        <li>
                            <a class="text-white" href="https://getbootstrap.com/docs/4.6/getting-started/introduction/">
                                Bootstrap4
                            </a>
                        <li>
                            <a class="text-white" href="https://datatables.net/examples/styling/bootstrap4">
                                Datatables
                            </a>
                        <li>
                            <a class="text-white" href="https://jquery.com/">
                                jQuery
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </small>
    </div>
</div>