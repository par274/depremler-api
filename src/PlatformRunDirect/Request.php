<?php

namespace PlatformRunDirect;

use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Request
{
	private $request;
	private $response;

	public function __construct()
	{
		$this->request = HttpRequest::createFromGlobals();
		$this->response = new HttpResponse();
	}

	public function getRequestUri()
	{
		return $this->request->getRequestUri();
	}

	public function getUri()
	{
		return $this->request->getUri();
	}

	public function getPathInfo()
	{
		return $this->request->getPathInfo();
	}

	public function setRequestUri()
	{
		$base  = dirname($this->request->server->get('PHP_SELF'));

		if (ltrim($base, '/'))
		{
			$this->request->server->set('REQUEST_URI', $this->getPathInfo());
		}

		$this->request->overrideGlobals();

		return $this->getRequestUri();
	}

	public function getHttpReferer()
	{
		return $this->request->server->get('HTTP_REFERER');
	}

	public function getRemoteAddr()
	{
		return $this->request->server->get('REMOTE_ADDR');
	}

	public function getServerName()
	{
		return $this->request->server->get('SERVER_NAME');
	}

	public function getRequestMethod()
	{
		return $this->request->server->get('REQUEST_METHOD');
	}

	public function setContentDisposition($filename, $file, $contentType, $attachment = false)
	{
		$fileContent = $file;
		$response = new BinaryFileResponse($fileContent);

		$response->headers->set('Content-Type', $contentType);
		if ($attachment)
		{
			$disposition = HeaderUtils::makeDisposition(
				HeaderUtils::DISPOSITION_ATTACHMENT,
				$filename
			);

			$response->headers->set('Content-Disposition', $disposition);
		}

		return $response->send();
	}

	public function setContentDispositionStreamed($filename, $data, $contentType, $attachment = false)
	{
		$response = new StreamedResponse();
		$response->setCallback(function () use ($data)
		{
			echo $data;
		});

		$response->headers->set('Content-Type', $contentType);
		if ($attachment)
		{
			$disposition = HeaderUtils::makeDisposition(
				HeaderUtils::DISPOSITION_ATTACHMENT,
				$filename
			);

			$response->headers->set('Content-Disposition', $disposition);
		}

		return $response->send();
	}

	public function setContentType($contentType)
	{
		$this->response->headers->set('Content-Type', $contentType);

		//$this->response->prepare($this->request);
		//$this->response->send();
	}

	protected function redirectResponse($url): RedirectResponse
	{
		$response = new RedirectResponse($url, 301);

		return $response->send();
	}
}
