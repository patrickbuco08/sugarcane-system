#!/usr/bin/env python3
"""
Compare laboratory Brix and Pol values with formula predictions.
Generates visualization showing percent error comparison.
"""

import pandas as pd
import json
import matplotlib.pyplot as plt
import numpy as np

def load_data():
    """Load dataset and model coefficients."""
    # Load CSV data
    df = pd.read_csv('sugarcane_dataset.csv')
    
    # Load model coefficients
    with open('model_coefficients.json', 'r') as f:
        coefficients = json.load(f)
    
    return df, coefficients

def calculate_prediction(row, coefficients, target):
    """
    Calculate predicted Brix or Pol using the formula:
    Value = Intercept + (R * Coef_R) + (S * Coef_S) + (T * Coef_T) + 
            (U * Coef_U) + (V * Coef_V) + (W * Coef_W)
    """
    intercept = coefficients[target]['intercept']
    coefs = coefficients[target]['coefficients']
    feature_order = coefficients['feature_order']
    
    # Calculate prediction using formula
    prediction = intercept
    for i, feature in enumerate(feature_order):
        prediction += row[feature] * coefs[i]
    
    return prediction

def calculate_percent_error(actual, predicted):
    """Calculate percent error: |actual - predicted| / actual * 100"""
    return abs(actual - predicted) / actual * 100

def main():
    # Load data
    df, coefficients = load_data()
    
    # Calculate predictions for Brix and Pol
    df['brix_predicted'] = df.apply(
        lambda row: calculate_prediction(row, coefficients, 'brix'), axis=1
    )
    df['pol_predicted'] = df.apply(
        lambda row: calculate_prediction(row, coefficients, 'pol'), axis=1
    )
    
    # Calculate percent errors
    df['brix_error'] = df.apply(
        lambda row: calculate_percent_error(row['brix'], row['brix_predicted']), axis=1
    )
    df['pol_error'] = df.apply(
        lambda row: calculate_percent_error(row['pol'], row['pol_predicted']), axis=1
    )
    
    # Print summary statistics
    print("=" * 60)
    print("COMPARISON SUMMARY")
    print("=" * 60)
    print(f"\nBrix Predictions:")
    print(f"  Mean Percent Error: {df['brix_error'].mean():.2f}%")
    print(f"  Median Percent Error: {df['brix_error'].median():.2f}%")
    print(f"  Max Percent Error: {df['brix_error'].max():.2f}%")
    print(f"  Min Percent Error: {df['brix_error'].min():.2f}%")
    
    print(f"\nPol Predictions:")
    print(f"  Mean Percent Error: {df['pol_error'].mean():.2f}%")
    print(f"  Median Percent Error: {df['pol_error'].median():.2f}%")
    print(f"  Max Percent Error: {df['pol_error'].max():.2f}%")
    print(f"  Min Percent Error: {df['pol_error'].min():.2f}%")
    print("=" * 60)
    
    # Create visualization
    fig, axes = plt.subplots(2, 2, figsize=(16, 12))
    fig.suptitle('Laboratory vs Formula Predictions Comparison', 
                 fontsize=16, fontweight='bold', y=0.995)
    
    samples = df['sample_code'].values
    x_pos = np.arange(len(samples))
    
    # Plot 1: Brix Comparison
    ax1 = axes[0, 0]
    width = 0.35
    ax1.bar(x_pos - width/2, df['brix'], width, label='Laboratory', 
            color='#3498db', alpha=0.8)
    ax1.bar(x_pos + width/2, df['brix_predicted'], width, label='Formula', 
            color='#e74c3c', alpha=0.8)
    ax1.set_xlabel('Sample Code', fontweight='bold')
    ax1.set_ylabel('Brix Value', fontweight='bold')
    ax1.set_title('Brix: Laboratory vs Formula', fontweight='bold', pad=10)
    ax1.set_xticks(x_pos)
    ax1.set_xticklabels(samples, rotation=45, ha='right')
    ax1.legend()
    ax1.grid(axis='y', alpha=0.3)
    
    # Plot 2: Pol Comparison
    ax2 = axes[0, 1]
    ax2.bar(x_pos - width/2, df['pol'], width, label='Laboratory', 
            color='#2ecc71', alpha=0.8)
    ax2.bar(x_pos + width/2, df['pol_predicted'], width, label='Formula', 
            color='#f39c12', alpha=0.8)
    ax2.set_xlabel('Sample Code', fontweight='bold')
    ax2.set_ylabel('Pol Value', fontweight='bold')
    ax2.set_title('Pol: Laboratory vs Formula', fontweight='bold', pad=10)
    ax2.set_xticks(x_pos)
    ax2.set_xticklabels(samples, rotation=45, ha='right')
    ax2.legend()
    ax2.grid(axis='y', alpha=0.3)
    
    # Plot 3: Brix Percent Error
    ax3 = axes[1, 0]
    bars3 = ax3.bar(x_pos, df['brix_error'], color='#9b59b6', alpha=0.8)
    ax3.axhline(y=df['brix_error'].mean(), color='red', linestyle='--', 
                linewidth=2, label=f"Mean: {df['brix_error'].mean():.2f}%")
    ax3.set_xlabel('Sample Code', fontweight='bold')
    ax3.set_ylabel('Percent Error (%)', fontweight='bold')
    ax3.set_title('Brix Percent Error', fontweight='bold', pad=10)
    ax3.set_xticks(x_pos)
    ax3.set_xticklabels(samples, rotation=45, ha='right')
    ax3.legend()
    ax3.grid(axis='y', alpha=0.3)
    
    # Plot 4: Pol Percent Error
    ax4 = axes[1, 1]
    bars4 = ax4.bar(x_pos, df['pol_error'], color='#1abc9c', alpha=0.8)
    ax4.axhline(y=df['pol_error'].mean(), color='red', linestyle='--', 
                linewidth=2, label=f"Mean: {df['pol_error'].mean():.2f}%")
    ax4.set_xlabel('Sample Code', fontweight='bold')
    ax4.set_ylabel('Percent Error (%)', fontweight='bold')
    ax4.set_title('Pol Percent Error', fontweight='bold', pad=10)
    ax4.set_xticks(x_pos)
    ax4.set_xticklabels(samples, rotation=45, ha='right')
    ax4.legend()
    ax4.grid(axis='y', alpha=0.3)
    
    plt.tight_layout()
    
    # Save the figure
    output_file = 'comparison_results.png'
    plt.savefig(output_file, dpi=300, bbox_inches='tight')
    print(f"\n✓ Visualization saved as '{output_file}'")
    
    # Optionally save detailed results to CSV
    results_df = df[['sample_code', 'variety', 
                      'brix', 'brix_predicted', 'brix_error',
                      'pol', 'pol_predicted', 'pol_error']]
    results_df.to_csv('comparison_results.csv', index=False)
    print(f"✓ Detailed results saved as 'comparison_results.csv'")
    
    # Show plot
    plt.show()

if __name__ == '__main__':
    main()
