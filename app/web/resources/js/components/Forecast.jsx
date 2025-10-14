import React, { useState, useEffect } from 'react';
import {
  LineChart,
  Line,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  Legend,
  ResponsiveContainer,
} from 'recharts';
import axios from 'axios';

const Forecast = () => {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await axios.get('/graphs/brix-and-lkgtc');
        // Format the data for charts
        const formattedData = response.data.map(item => ({
          ...item,
          week_of_formatted: new Date(item.week_of).toISOString().split('T')[0],
        }));
        setData(formattedData);
        setLoading(false);
      } catch (err) {
        setError(err.message);
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (loading) {
    return (
      <div className="flex items-center justify-center p-8">
        <div className="text-gray-600">Loading graphs...</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="flex items-center justify-center p-8">
        <div className="text-red-600">Error loading data: {error}</div>
      </div>
    );
  }

  return (
    <div className="space-y-8 mt-8">
      {/* Graph 1: Brix & LKGTC Trend */}
      <div className="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 className="text-xl font-bold mb-4">Graph 1: Brix & LKGTC Trend (Weekly)</h2>
        <ResponsiveContainer width="100%" height={400}>
          <LineChart
            data={data}
            margin={{ top: 5, right: 30, left: 20, bottom: 5 }}
          >
            <CartesianGrid strokeDasharray="3 3" />
            <XAxis
              dataKey="week_of_formatted"
              label={{ value: 'Week of', position: 'insideBottom', offset: -5 }}
            />
            <YAxis
              label={{ value: 'Brix / LKGTC', angle: -90, position: 'insideLeft' }}
            />
            <Tooltip />
            <Legend />
            <Line
              type="monotone"
              dataKey="avg_brix"
              stroke="#ff8c00"
              strokeWidth={2}
              dot={{ fill: '#ff8c00', r: 4 }}
              name="Avg Brix (°Bx)"
            />
            <Line
              type="monotone"
              dataKey="lkg_tc"
              stroke="#1e90ff"
              strokeWidth={2}
              dot={{ fill: '#1e90ff', r: 4 }}
              name="LKGTC"
            />
          </LineChart>
        </ResponsiveContainer>
      </div>

      {/* Graph 2: Weekly Price & Profit */}
      <div className="bg-white rounded-lg shadow-lg p-6">
        <h2 className="text-xl font-bold mb-4">Graph 2: Weekly Price & Profit (Weekly Aggregation)</h2>
        <ResponsiveContainer width="100%" height={400}>
          <LineChart
            data={data}
            margin={{ top: 5, right: 60, left: 20, bottom: 5 }}
          >
            <CartesianGrid strokeDasharray="3 3" />
            <XAxis
              dataKey="week_of_formatted"
              label={{ value: 'Week of', position: 'insideBottom', offset: -5 }}
            />
            <YAxis
              yAxisId="left"
              label={{ value: 'Price (₱/LKG)', angle: -90, position: 'insideLeft' }}
              stroke="#22c55e"
            />
            <YAxis
              yAxisId="right"
              orientation="right"
              label={{ value: 'Profit (₱)', angle: 90, position: 'insideRight' }}
              stroke="#dc2626"
              tickFormatter={(value) => `${(value / 1e6).toFixed(2)}e6`}
            />
            <Tooltip
              formatter={(value, name) => {
                if (name === 'Profit (₱)') {
                  return [`₱${value.toLocaleString()}`, name];
                }
                return [`₱${value}`, name];
              }}
            />
            <Legend />
            <Line
              yAxisId="left"
              type="monotone"
              dataKey="b_domestic"
              stroke="#22c55e"
              strokeWidth={2}
              dot={{ fill: '#22c55e', r: 4 }}
              name="B Domestic Price (₱/LKG)"
            />
            <Line
              yAxisId="right"
              type="monotone"
              dataKey="profit"
              stroke="#dc2626"
              strokeWidth={2}
              strokeDasharray="5 5"
              dot={{ fill: '#dc2626', r: 4 }}
              name="Profit (₱)"
            />
          </LineChart>
        </ResponsiveContainer>
      </div>
    </div>
  );
};

export default Forecast;