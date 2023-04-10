import React, { FunctionComponent } from "react";
import { HistoricalData } from "@/types";

interface Props {
    historicalData: HistoricalData[];
}
const List: FunctionComponent<Props> = ({ historicalData }: Props) => {
    return (
        <div className="container mx-auto mt-8">
            <section className="mt-6 grid grid-cols-12 items-center border p-4 shadow-sm">
                <div className="col-span-12 text-center">
                    <span className="text-m text-gray-700 font-medium">
                        Historical data
                    </span>
                </div>
            </section>

            <div className="flex flex-col">
                <div className="overflow-x-auto border border-gray-200 border-t-0">
                    <table className="min-w-full divide-y divide-gray-200">
                        <thead className="">
                            <tr>
                                <th className="py-4 text-sm font-medium uppercase text-gray-700 ps-4 text-left border-r-2">
                                    Date
                                </th>
                                <th className="py-4 text-sm font-medium uppercase text-gray-700 ps-4 text-left border-r-2">
                                    Open
                                </th>
                                <th className="py-4 text-sm font-medium uppercase text-gray-700 ps-4 text-left border-r-2">
                                    High
                                </th>
                                <th className="py-4 text-sm font-medium uppercase text-gray-700 ps-4 text-left border-r-2">
                                    Low
                                </th>
                                <th className="py-4 text-sm font-medium uppercase text-gray-700 ps-4 text-left border-r-2">
                                    Close
                                </th>
                                <th className="py-4 text-sm font-medium uppercase text-gray-700 ps-4 text-left">
                                    Volume
                                </th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-200 bg-white">
                            {historicalData?.length > 0 ? (
                                <>
                                    {historicalData.map((item, index) => (
                                        <tr key={index}>
                                            <td className="whitespace-nowrap px-4 py-4 text-sm border-r-2">
                                                {item.date}
                                            </td>
                                            <td className="whitespace-nowrap px-4 py-4 text-sm border-r-2">
                                                {item.open}
                                            </td>
                                            <td className="whitespace-nowrap px-4 py-4 text-sm border-r-2">
                                                {item.high}
                                            </td>
                                            <td className="whitespace-nowrap px-4 py-4 text-sm border-r-2">
                                                {item.low}
                                            </td>
                                            <td className="whitespace-nowrap px-4 py-4 text-sm border-r-2">
                                                {item.close}
                                            </td>
                                            <td className="whitespace-nowrap px-4 py-4 text-sm">
                                                {item.volume}
                                            </td>
                                        </tr>
                                    ))}
                                </>
                            ) : (
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td className="px-4 py-4 text-right">
                                        No data found
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
};

export default List;
