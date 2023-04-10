import React, { FormEvent, FunctionComponent, useState } from "react";
import "flatpickr/dist/themes/airbnb.css";
import Flatpickr from "react-flatpickr";
import { useForm, usePage } from "@inertiajs/react";
import Validator, { RegisterCallback } from "validatorjs";
import en from "validatorjs/src/lang/en";
import { FormError, CompanySymbolList } from "@/types";
import _ from "lodash";
import BtnLoader from "@/components/BtnLoader";

interface Props {
    companySymbolList: CompanySymbolList;
}
const FilterForm: FunctionComponent<Props> = ({ companySymbolList }: Props) => {
    Validator.setMessages("en", en);
    const { data, setData, post, processing } = useForm({
        company_symbol: "",
        start_date: "",
        end_date: "",
        email: "",
    });

    const [clientSideErrors, setClientSideErrors] = useState<FormError>(
        {} as FormError
    );

    const { errors } = usePage().props;

    Validator.register(
        "before_or_equal_today",
        function (value: string) {
            const currentDate = new Date().toISOString().split("T")[0];
            if (new Date(currentDate).getTime() >= new Date(value).getTime()) {
                return true;
            }
            return false;
        } as RegisterCallback,
        ":attribute must be less or equal than current date"
    );

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        setClientSideErrors({});

        const validation = new Validator(data, {
            company_symbol: `required|in:${Object.keys(companySymbolList)}`,
            start_date:
                "required|date|before_or_equal:end_date|before_or_equal_today",
            end_date:
                "required|date|after_or_equal:start_date|before_or_equal_today",
            email: "required|email",
        });
        if (validation.fails()) {
            setClientSideErrors(validation.errors.errors);
            return;
        }
        post("/");
    };
    return (
        <div className="mt-10 bg-white shadow border rounded-sm">
            <div className="border-b border-gray-200 px-4 py-5 sm:px-11">
                Filter Form
            </div>
            <form className="mt-4 mx-auto w-1/2" onSubmit={handleSubmit}>
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700 mb-1">
                        Company Symbol
                    </label>
                    <select
                        name="company_symbol"
                        id="company-symbol"
                        onChange={(e) =>
                            setData("company_symbol", e.target.value)
                        }
                        className="block w-full ps-3 border border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 rounded mt-2"
                    >
                        <option value="">--Select--</option>
                        {Object.keys(companySymbolList).map((symbol) => {
                            return (
                                <option key={symbol} value={symbol}>
                                    {symbol}
                                </option>
                            );
                        })}
                    </select>
                    <small className="text-red-600 font-medium">
                        {_.first(clientSideErrors.company_symbol) ||
                            errors?.company_symbol}
                    </small>
                </div>
                <div className="mb-4">
                    <div className="grid grid-cols-12">
                        <div className="col-span-6 mr-6">
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Start Date
                            </label>
                            <Flatpickr
                                placeholder="Start Date"
                                name="start_date"
                                options={{
                                    altInput: true,
                                    altFormat: "Y-m-d",
                                    dateFormat: "Y-m-d",
                                    static: true,
                                }}
                                onChange={(date, dateStr) => {
                                    setData("start_date", dateStr);
                                }}
                                className="appearance-none block w-full pl-3 pr-3 py-2 border border-gray-300 rounded shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm mt-1"
                            />
                            <small className="text-red-600 font-medium">
                                {_.first(clientSideErrors.start_date) ||
                                    errors?.start_date}
                            </small>
                        </div>
                        <div className="col-span-6">
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                End Date
                            </label>
                            <Flatpickr
                                placeholder="End Date"
                                name="end_date"
                                options={{
                                    altInput: true,
                                    altFormat: "Y-m-d",
                                    dateFormat: "Y-m-d",
                                    static: true,
                                }}
                                onChange={(date, dateStr) => {
                                    setData("end_date", dateStr);
                                }}
                                className="appearance-none block w-full pl-3 pr-3 py-2 border border-gray-300 rounded shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm mt-1"
                            />
                            <small className="text-red-600 font-medium">
                                {_.first(clientSideErrors.end_date) ||
                                    errors?.end_date}
                            </small>
                        </div>
                    </div>
                </div>
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="text"
                        placeholder="Email"
                        onChange={(e) => setData("email", e.target.value)}
                        className="appearance-none block w-full pl-3 pr-3 py-2 border border-gray-300 rounded shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm mt-1"
                    ></input>
                    <small className="text-red-600 font-medium">
                        {_.first(clientSideErrors.email) || errors?.email}
                    </small>
                </div>
                <div className="mb-8">
                    <button
                        type="submit"
                        disabled={processing}
                        className="w-full items-center inline-flex gap-2 justify-center border rounded shadow-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:ring-blue-500 px-5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-300"
                    >
                        <BtnLoader isLoading={processing} />
                        {processing ? "Loading..." : "Submit"}
                    </button>
                </div>
            </form>
        </div>
    );
};

export default FilterForm;
