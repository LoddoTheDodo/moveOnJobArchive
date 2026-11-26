<?php

namespace App\elasticsearch;


use App\Models\ArchivedJobModel;
use DateTime;

class JobDocument {
    public function createJobDocument(ArchivedJobModel $mModel) {
        $found = new DateTime($mModel->found);
        $lastExamined = new DateTime($mModel->lastExamined);
        $statusSince = new DateTime($mModel->statusSince);

        $aData = [
            "title" => $mModel->title,
            "extractedContent" => $mModel->extractedContent,
            "found" => $found->format('Y-m-d'),
            "lastExamined" => $lastExamined->format('Y-m-d'),
            "statusSince" => $statusSince->format('Y-m-d'),
            "jobData" => $mModel->attributesToArray(),
            "companyData" => $mModel->company->attributesToArray(),
            "companyId" => $mModel->company->id,
            "companyRobotId" => $mModel->company->robotId,
            "companyName" => $mModel->company->companyName,
            "companyPrimaryAddress" => $mModel->company->primaryAddress,
            "companyPrimaryAddressZip" => $mModel->company->primaryAddressZip,
            "companyPrimaryAddressCity" => $mModel->company->primaryAddressCity,
            "companyUrl" => $mModel->company->url,
            "companyBaseDescription" => $mModel->company->baseDescription,
            "companyUnsolicitedApplications" => $mModel->company->unsolicitedApplications,
            "companyLon" => $mModel->company->lon,
            "companyLat" => $mModel->company->lat,
            "categories" => [],
            "categoryData" => []
        ];

        foreach($mModel->category as $category) {
            $aData["categories"][] = [
                "id" => $category->id,
                "title" => $category->title,
                "sequence" => $category->sequence
            ];
            $aData["categoryData"][] = $category->attributesToArray();
        }

        return $aData;
    }
}
