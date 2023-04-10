export type AnyObject = {
    [key: string]: unknown;
};
export type FormError = {
    [key: string]: string[];
};

export type CompanyInfo = {
    "Company Name": string;
    "Financial Status": string;
    "Market Category": string;
    "Round Lot Size": number;
    "Security Name": string;
    Symbol: string;
    "Test Issue": string;
};

export type HistoricalData = {
    date: number;
    open: number;
    high: number;
    low: number;
    close: number;
    volume: number;
    adjclose: number;
};

export type CompanySymbolList = {
    [key: string]: string;
};
