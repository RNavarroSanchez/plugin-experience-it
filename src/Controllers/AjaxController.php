<?php

namespace ULA\Controllers;

use ULA\Services\UserService;
use ULA\Controllers\RequestValidator;

class AjaxController
{
    private UserService $service;
    private RequestValidator $validator;

    public function __construct(UserService $service, RequestValidator $validator)
    {
        $this->service = $service;
        $this->validator = $validator;
    }

    public function handleFetchUsers(): void
    {
        check_ajax_referer('ula_fetch_users', 'nonce');

        $filters = $this->validator->filters($_POST);
        $page = $this->validator->page($_POST);
        $perPage = $this->validator->perPage($_POST);

        $result = $this->service->getUsers($filters, $page, $perPage);

        wp_send_json_success($result);
    }
}
