<?php

class SearchCompany
{
    public function analyzeCompany()
    {
        preg_match_all("/(«(.*)»)/U",
                       "Ирина «Худайбердыева», «генеральный» управляющий загородного клуба «Ильдорф»",
                       $result);
        return $result;
    }
}
