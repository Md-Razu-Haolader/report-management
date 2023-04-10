import React, { FunctionComponent } from "react";

import FilterForm from "@/Pages/report/filter-form";
import List from "@/Pages/report/list";
import Chart from "@/Pages/report/chart";
import { HistoricalData, CompanySymbolList } from "@/types";

interface Props {
    companySymbolList: CompanySymbolList;
    historicalData: HistoricalData[];
}
const Report: FunctionComponent<Props> = ({
    companySymbolList,
    historicalData,
}: Props) => {
    return (
        <div className="container mx-auto px-4">
            <FilterForm companySymbolList={companySymbolList} />
            <Chart historicalData={historicalData} />
            <List historicalData={historicalData} />
        </div>
    );
};

export default Report;
