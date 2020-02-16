<!DOCTYPE HTML>
<!--
	Spectral by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>MIDAS.LAB - Publications</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<script type="text/javascript" src="assets/js/util_addon.js"></script>
	</head>
	<body class="is-preload">

		<!-- Page Wrapper -->
			<div id="page-wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="index.html">MIDAS.LAB</a></h1>
						<nav id="nav">
							<ul>
								<li class="special">
									<a href="#menu" class="menuToggle"><span>Menu</span></a>
									<div id="menu">
										<ul>
											<li><a href="index.html">Home</a></li>
											<li><a href="news.html">News</a></li>
											<li><a href="people.html">About us</a></li>
											<li><a href="research.html">Research</a></li>
											<li><a href="teaching.html">Teaching</a></li>
											<li><a href="publications.php">Publications</a></li>
										</ul>
									</div>
								</li>
							</ul>
						</nav>
					</header>

				<!-- Main -->
					<article id="main">
						<header>
							<h2>Publications</h2>
							<p>recent journal and conference papers</p>
						</header>
						<section class="wrapper style5">
							<div class="inner">

								<h3>Publications</h3>

								<?php

								require __DIR__ . '/vendor/autoload.php';

								use GScholarProfileParser\DomCrawler\ProfilePageCrawler;
								use GScholarProfileParser\Iterator\PublicationYearFilterIterator;
								use GScholarProfileParser\Parser\PublicationParser;
								use GScholarProfileParser\Parser\PublicationParserURL;
								use GScholarProfileParser\Entity\Publication;
								use Goutte\Client;

								/** @var Client $client */
								$client = new Client();

								// Thomas, Sergios,
								$googleids = array('Oo6NZZcAAAAJ', '7vCEi-gAAAAJ');
								$publications = array();
								foreach($googleids as $uid)
								{
								  /** @var ProfilePageCrawler $crawler */
								  $crawler = new ProfilePageCrawler($client, $uid); // the second parameter is the scholar's profile id

								  /** @var PublicationParser $parser */
								  $parser = new PublicationParser($crawler->getCrawler());

								  /** @var array<int, array<string, string>> $publications */
								  $publicationsCurr = $parser->parse();
								  $publications=array_merge($publications, $publicationsCurr);
								}
								// remove duplicates
								$publications = array_intersect_key($publications, array_unique(array_column($publications, 'title')));

								// hydrates items of $publications into Publication
								foreach ($publications as &$publication) {
										/** @var Publication $publication */
								    $publication = new Publication($publication);
								}
								unset($publication);

								$currYear = (int)date('o');

								for($year=$currYear; $year>=2005; $year--){

								    /** @var PublicationYearFilterIterator $publications2018 */
								    $publicationsCurr = new PublicationYearFilterIterator(new ArrayIterator($publications), $year);

								    //print_r($publicationsCurr);
								    if(iterator_count($publicationsCurr) == 0)
								    {
								      continue;
								    }
								    echo "<h4>" . $year . "</h4><p>";
								    // displays list of publications published in 2018
								    /** @var Publication $publication */
								    foreach ($publicationsCurr as $publication) {
								        //echo $publication->getPublicationURL(), "<br>";
								        echo $publication->getAuthors() . "<br>";
								        echo "<b><a href='" . $publication->getPublicationURL() . "' target='_blank'>" . $publication->getTitle() . "</a></b><br>";
								        echo "<i>" . $publication->getPublisherDetails() . "</i>, ";
								        //echo $publication->getNbCitations(), " ";
								        //echo $publication->getCitationsURL(), "<br>";
								        echo $publication->getYear() . "</p><br>";
								    }
										echo "<hr />";
								} ?>

							</div>
						</section>
					</article>

				<!-- Footer -->
		<footer id="footer">
						<ul class="icons">
							<li><a href="https://twitter.com/lab_midas" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
							<!--
							<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
							<li><a href="#" class="icon brands fa-dribbble"><span class="label">Dribbble</span></a></li>
							//-->
							<li><a href="https://www.github.com/lab-midas" class="icon brands fa-github"><span class="label">Github</span></a></li>
							<li><script>getMail('info', 'midaslab.org', ' class=\"icon solid fa-envelope\">');</script><span class="label">Email</span></a></li>
						</ul>
						<ul class="copyright">
							<li>&copy; 2020 MIDAS.LAB </li><li>Design: <a href="https://html5up.net/">HTML5 UP</a></li><li><a href="privacy.html">Privacy/Imprint</a></li>
						</ul>
					</footer>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
