<?php

namespace App\Repositories\MSSQL;

use App\Contracts\Repositories\BranchRepository as BranchRepositoryContract;
use App\Models\Branch;

class BranchRepository extends BaseRepository implements BranchRepositoryContract
{
    /**
     * {@inheritdoc}
     */
    public function suppressedUPRNs(Branch $branch)
    {
        $ownedUPRNs = $this->db->table('load.PropertiesAvailableOnMarket AS A')
            ->join('load.CompanyBranch AS CB', 'A.CompanyBrandID', '=', 'CB.BrandId')
            ->where('CB.BranchID', $branch->id)
            ->pluck('UPRN')
            ->all();

        $suppressedUPRNs = $this->db->table('load.UserSuppressions AS S')
            ->where('S.SiteId', config('prospect.site_id'))
            ->where('S.BranchID', $branch->id)
            ->pluck('UPRN')
            ->all();

        $suppressedBrandUPRNs = $this->db->table('load.PropertiesAvailableOnMarket AS A')
            ->join('load.UserBranchBrandSuppression AS BRS', 'A.CompanyBrandID', '=', 'BRS.SuppressedBrandId')
            ->where('BRS.SiteId', config('prospect.site_id'))
            ->where('BRS.BranchID', $branch->id)
            ->pluck('UPRN')
            ->all();

        return array_merge(
            $ownedUPRNs,
            $suppressedUPRNs,
            $suppressedBrandUPRNs
        );
    }
}
