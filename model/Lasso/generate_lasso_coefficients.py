#!/usr/bin/env python3
"""
Generate Lasso regression coefficients from the sugarcane dataset.
Saves coefficients to lasso_coefficients.json in the same directory.
"""

import pandas as pd
import json
import os
from sklearn.linear_model import Lasso

def main():
    # Get script directory and parent directory
    script_dir = os.path.dirname(os.path.abspath(__file__))
    parent_dir = os.path.dirname(script_dir)
    
    # Load the dataset from parent directory
    input_csv = os.path.join(parent_dir, "sugarcane_dataset.csv")
    print(f"Loading dataset from: {input_csv}")
    df = pd.read_csv(input_csv)

    # Prepare features and targets
    X = df[['R', 'S', 'T', 'U', 'V', 'W']]
    y_brix = df['brix']
    y_pol = df['pol']

    # Train Lasso Regression models
    brix_model = Lasso(alpha=0.1)
    pol_model = Lasso(alpha=0.1)

    brix_model.fit(X, y_brix)
    pol_model.fit(X, y_pol)

    # Prepare output dictionary
    result = {
        "brix_formula": {
            "intercept": brix_model.intercept_,
            "coefficients": dict(zip(X.columns, brix_model.coef_))
        },
        "pol_formula": {
            "intercept": pol_model.intercept_,
            "coefficients": dict(zip(X.columns, pol_model.coef_))
        }
    }

    # Save to JSON in the same directory as this script
    output_json = os.path.join(script_dir, "lasso_coefficients.json")
    with open(output_json, "w") as f:
        json.dump(result, f, indent=4)

    print(f"\n✓ Lasso coefficients saved to: {output_json}")

if __name__ == "__main__":
    main()
