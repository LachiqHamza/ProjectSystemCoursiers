import React from 'react';
import { Link } from 'react-router-dom';
import { Table, Button } from 'antd';

const CoursieProfile = () => {
  // Sample data for the table
  const data = [
    { id: 1, number: 1, source: 'Source A', destination: 'Destination X', telephone: '+1234567890' },
    { id: 2, number: 2, source: 'Source B', destination: 'Destination Y', telephone: '+1987654321' },
    { id: 3, number: 3, source: 'Source C', destination: 'Destination Z', telephone: '+1122334455' },
    // Add more rows as needed
  ];

  // Columns configuration for Ant Design Table
  const columns = [
    {
      title: 'Number',
      dataIndex: 'number',
      key: 'number',
    },
    {
      title: 'Source Address',
      dataIndex: 'source',
      key: 'source',
    },
    {
      title: 'Destination Address',
      dataIndex: 'destination',
      key: 'destination',
    },
    {
      title: 'Telephone',
      dataIndex: 'telephone',
      key: 'telephone',
    },
    {
      title: 'Action',
      key: 'action',
      render: () => (
        <Button type="primary">Mark as done</Button>
      ),
    },
  ];

  return (
    <div style={{ textAlign: 'center', padding: '20px' }}>
      <h1>Welcome ***</h1>
      <Button style={{ marginBottom: '10px' }}>
        <Link to="/" style={{ color: 'inherit' }}>Logout</Link>
      </Button>
      <Table
        dataSource={data}
        columns={columns}
        bordered
        pagination={false}
        style={{ width: '80%', margin: 'auto' }}
        rowClassName={() => 'editable-row'}
      />
    </div>
  );
};

export default CoursieProfile;
