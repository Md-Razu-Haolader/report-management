import React, { ReactElement } from "react";
interface Props {
    isLoading?: boolean;
    className?: string;
    children?: ReactElement | ReactElement[];
}

const BtnLoader = ({ className = "", isLoading = false, children }: Props) => {
    return (
        <>
            {isLoading ? (
                <div
                    className={`${className} inline-block h-12 w-12 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]`}
                ></div>
            ) : (
                children
            )}
        </>
    );
};

export default BtnLoader;
