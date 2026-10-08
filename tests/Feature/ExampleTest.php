<?php

test('the home page redirects to reports', function () {
    $response = $this->get('/');

    $response->assertRedirectToRoute('reports');
});
