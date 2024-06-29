import { FieldValidatorConfig } from './field-validator-config.interface';

export interface FieldConfig {
    formControlName: string;
    value?: string;
    type: string;
    label: string;
    required: boolean;
    validators: FieldValidatorConfig[];
}