import React, { FunctionComponent } from "react";
import {
    LineChart,
    Line,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend,
} from "recharts";
import { format } from "date-fns";
import { HistoricalData } from "@/types";

interface Props {
    historicalData: HistoricalData[];
}
const Chart: FunctionComponent<Props> = ({ historicalData }: Props) => {
    const formatTimestampToDate = (timestamp: number): string => {
        let convertedTimestamp = timestamp;
        if (timestamp.toString().length === 10) {
            convertedTimestamp = timestamp * 1000;
        }
        return format(new Date(convertedTimestamp), "dd MMM yyyy");
    };

    return (
        <div className="container mx-auto mt-8">
            <section className="mt-6 grid grid-cols-12 items-center border p-4 shadow-sm">
                <div className="col-span-12">
                    <LineChart width={1200} height={300} data={historicalData}>
                        <CartesianGrid strokeDasharray="3 3" />
                        <XAxis
                            dataKey="date"
                            padding={{ left: 30, right: 30 }}
                            tickFormatter={(timestamp) =>
                                formatTimestampToDate(timestamp)
                            }
                        />
                        <YAxis />
                        <Tooltip labelFormatter={formatTimestampToDate} />
                        <Legend />
                        <Line
                            type="monotone"
                            dataKey="open"
                            stroke="#8884d8"
                            activeDot={{ r: 8 }}
                        />
                        <Line
                            type="monotone"
                            dataKey="close"
                            stroke="#82ca9d"
                        />
                    </LineChart>
                </div>
            </section>
        </div>
    );
};

export default Chart;
