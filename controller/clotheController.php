<?php

require_once('./model/clothe.php');
require 'vendor/autoload.php';



class clotheController
{







	public function getImg()
	{
		$clothe = new clothe();

		$resp = $clothe->getImgData();
		return $resp;
	}
	public function getAll()
	{
		$clothe = new clothe();

		$resp = $clothe->getData();
		return $resp;
	}

	public function showAllPurchased()
	{
		$clothe = new clothe();

		$resp = $clothe->getAllPurchased();
		return $resp;
	}

	public function getclothe($title)
	{
		$clothe = new clothe();

		$resp = $clothe->findByTitle($title);
		// $resp = $clothe->getData();
		return $resp;
	}

	public function buy($title)
	{
		// echo $title;
		$clothe = new clothe();
		$clothe->addToList($title);
	}

	public function destroy()
	{
		$clothe = new clothe();

		$clothe->removeAllPurchased();
	}
}

?>