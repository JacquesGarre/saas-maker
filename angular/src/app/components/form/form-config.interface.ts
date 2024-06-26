import { Observable } from "rxjs";
import { FieldConfig } from "./field/field-config.interface";

export interface FormConfig {
  fields: FieldConfig[];
  submitBtnLabel: string;
  submitAction: (values: any) => Observable<any>;
  afterSubmitAction?: (response: any) => Observable<any>;
  afterSubmitRedirection?: {
    route: string,
    extras?: {}
  };
}