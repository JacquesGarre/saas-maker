import { FieldConfig } from "./field/field-config.interface";

export interface FormConfig {
    fields: FieldConfig[];
    submitBtnLabel: string;
    submitAction: (values: any) => void;
}