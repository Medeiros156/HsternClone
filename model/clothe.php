<?php
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

//peça de roupa
class Clothe
{





	public function getData()
	{

		$client = new Client();
		$crawler = $client->request('GET', 'https://www.hstern.com.br/');

		$result = [];

		$crawler->filter('li')->filter('div.shelf__common--item')->each(function ($node) use (&$result) {
			// $img = $node->filter('img')->attr('src');
			$img = $node->filter('img')->each(function ($imgNode) use (&$result) {
				$src = $imgNode->attr('src');
				// echo 'IMG: ' . $img;
				return $src;
			});
			$title = $node->attr('title');
			$price = $node->filter('span.shelf__common--price--best-price')->text();

			$result[] = [
				'title' => $title,
				'price' => $price,
				'img' => $img
			];
		});

		return $result;
	}
	public function getImgData()
	{

		$client = new Client();
		$crawler = $client->request('GET', 'https://www.hstern.com.br/');

		$result = [];

		$crawler->filter('div .home__departments--row')->filter('img')->each(function ($node) use (&$result) {
			$src = $node->attr('src');
			if (strpos($src, 'http') === 0) {
				$result[] = $src
				;
			}
		});

		return $result;
	}


	public function showAll()
	{

		$clothes = file_get_contents('./assets/storage/clothes.json');
		return $clothes;
	}

	public function getAllPurchased()
	{

		$clothes = file_get_contents('./assets/storage/purchased.json');
		return $clothes;
	}

	public function findByTitle($title)
	{

		$items = Clothe::getData();

		foreach ($items as $item) {
			if ($item['title'] === $title) {
				// Match found, return the item
				return $item;
			}
		}

		// No match found
		return null;
	}

	public function addToList($title)
	{
		//adiciona a peça de roupa a lista de comprados

		$clothePurchased = Clothe::findByTitle($title);
		$list = json_decode(file_get_contents('./assets/storage/purchased.json'));
		$ok = true;
		// echo 'title' . $title;
		// echo var_dump($clothePurchased);
		// echo var_dump($list);

		foreach ($list as $clothe) {
			// echo $clothe->type;
			if ($clothePurchased['title'] == $clothe->title) {
				$ok = false;
			}
		}

		if ($ok) {
			array_push($list, $clothePurchased);

			$jsonList = json_encode($list);

			file_put_contents('./assets/storage/purchased.json', $jsonList);
		}
	}

	public function removeAllPurchased()
	{

		file_put_contents('./assets/storage/purchased.json', '[]');
	}

}