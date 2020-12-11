<?php

namespace App\Repositories\MSSQL;

use App\Contracts\Repositories\BranchRepository;
use App\Contracts\Repositories\PropertyRepository as PropertyRepositoryContract;
use App\Models\Branch;
use App\Prospect;

class PropertyRepository extends BaseRepository implements PropertyRepositoryContract
{
    /**
     * {@inheritdoc}
     */
    public function newInstructionsOnMarketSaleAudiences(Branch $branch, $options = [])
    {
        $filterOptions = $this->filterOptions($options);

        return $this->db->table('report.OMNewInstructionsDurationOnMarket AS O')
            ->join('load.Properties AS P', 'O.UPRN', '=', 'P.UPRN')
            ->whereIn('O.PostcodeDistrict', $branch->postcodeDistricts())
            ->whereNotIn('P.UPRN', Prospect::call(BranchRepository::class.'@suppressedUPRNs', [$branch]))
            ->whereNotIn('P.UPRN', function ($query) use ($branch, $filterOptions) {
                $query->select('CU.UPRN')
                    ->distinct()
                    ->from('load.CampaignUPRNs AS CU')
                    ->join('load.Campaigns AS C', function ($join) {
                        $join->on('CU.CampaignId', '=', 'C.CampaignId')
                            ->on('CU.SiteId', '=', 'C.SiteId');
                    })
                    ->where('C.BranchID', $branch->id)
                    ->where('C.SiteId', config('prospect.site_id'))
                    ->whereIn('C.Status', ['executed', 'downloaded']);

                if ($filterOptions['previously_targeted']) {
                    $query->where('TargetedWeeks', '<', $filterOptions['targeted_weeks']);
                }
            })
            ->select('P.Longitude', 'P.Latitude', 'P.UPRN', 'P.Address1', 'P.Town', 'P.Postcode', 'O.CompanyBrand', 'O.CurrentPrice')
            ->paginate(15);
    }

    /**
     * {@inheritdoc}
     */
    public function withdrawnOnMarketSaleAudiences(Branch $branch, $options = [])
    {
        $filterOptions = $this->filterOptions($options);

        return $this->db->table('report.OMWithdrawnDurationOnMarket AS O')
            ->join('load.Properties AS P', 'O.UPRN', '=', 'P.UPRN')
            ->whereIn('O.PostcodeDistrict', $branch->postcodeDistricts())
            ->whereNotIn('P.UPRN', Prospect::call(BranchRepository::class.'@suppressedUPRNs', [$branch]))
            ->whereNotIn('P.UPRN', function ($query) use ($branch, $filterOptions) {
                $query->select('CU.UPRN')
                    ->distinct()
                    ->from('load.CampaignUPRNs AS CU')
                    ->join('load.Campaigns AS C', function ($join) {
                        $join->on('CU.CampaignId', '=', 'C.CampaignId')
                            ->on('CU.SiteId', '=', 'C.SiteId');
                    })
                    ->where('C.BranchID', $branch->id)
                    ->where('C.SiteId', config('prospect.site_id'))
                    ->whereIn('C.Status', ['executed', 'downloaded']);

                if ($filterOptions['previously_targeted']) {
                    $query->where('TargetedWeeks', '<', $filterOptions['targeted_weeks']);
                }
            })
            ->select('P.Longitude', 'P.Latitude', 'P.UPRN', 'P.Address1', 'P.Town', 'P.Postcode', 'O.CompanyBrand', 'O.CurrentPrice')
            ->paginate(15);
    }

    /**
     * {@inheritdoc}
     */
    public function fallThroughOnMarketSaleAudiences(Branch $branch, $options = [])
    {
        $filterOptions = $this->filterOptions($options);

        return $this->db->table('report.OMFallenThroughDurationOnMarket AS O')
            ->join('load.Properties AS P', 'O.UPRN', '=', 'P.UPRN')
            ->where('O.FallenThroughDate', '>=', 'cast(dateadd(d, -7, GetDate()) as date)')
            ->whereIn('O.PostcodeDistrict', $branch->postcodeDistricts())
            ->whereNotIn('P.UPRN', Prospect::call(BranchRepository::class.'@suppressedUPRNs', [$branch]))
            ->whereNotIn('P.UPRN', function ($query) use ($branch, $filterOptions) {
                $query->select('CU.UPRN')
                    ->distinct()
                    ->from('load.CampaignUPRNs AS CU')
                    ->join('load.Campaigns AS C', function ($join) {
                        $join->on('CU.CampaignId', '=', 'C.CampaignId')
                            ->on('CU.SiteId', '=', 'C.SiteId');
                    })
                    ->where('C.BranchID', $branch->id)
                    ->where('C.SiteId', config('prospect.site_id'))
                    ->whereIn('C.Status', ['executed', 'downloaded']);

                if ($filterOptions['previously_targeted']) {
                    $query->where('TargetedWeeks', '<', $filterOptions['targeted_weeks']);
                }
            })
            ->select('P.Longitude', 'P.Latitude', 'P.UPRN', 'P.Address1', 'P.Town', 'P.Postcode', 'O.CompanyBrand', 'O.CurrentPrice')
            ->paginate(15);
    }

    protected function filterOptions($options = [])
    {
        return Prospect::filterAudiencesOptions($options);
    }
}
