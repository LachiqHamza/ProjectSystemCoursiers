import React, { useState, useEffect } from 'react';
import { Table, Select, Button, message, Layout } from 'antd';
import axios from 'axios';

const { Content } = Layout;

const AdminComponent = () => {
  const [demandes, setDemandes] = useState([]);
  const [coursiers, setCoursiers] = useState([]);

  const fetchDemandes = async () => {
    try {
      const response = await axios.get('http://127.0.0.1:8000/api/demandes/finddemandes/newdemandes');
      setDemandes(response.data);
    } catch (error) {
      message.error('Failed to fetch demandes.');
    }
  };

  const fetchCoursiers = async () => {
    try {
      const response = await axios.get('http://127.0.0.1:8000/api/courciers/all');
      setCoursiers(response.data);
    } catch (error) {
      message.error('Failed to fetch coursiers.');
    }
  };

  useEffect(() => {
    fetchDemandes();
    fetchCoursiers();
  }, []);

  const handleStatusChange = (id, value) => {
    const updatedDemandes = demandes.map((demande) =>
      demande.id_demande === id ? { ...demande, status: value } : demande
    );
    setDemandes(updatedDemandes);
  };

  const handleCoursierChange = (id, value) => {
    const updatedDemandes = demandes.map((demande) =>
      demande.id_demande === id ? { ...demande, id_coursier: value } : demande
    );
    setDemandes(updatedDemandes);
  };

  const handleUpdate = async (demande) => {
    const adminId = localStorage.getItem('clientId');
    try {
      await axios.put(`http://localhost:8000/api/demandes/${demande.id_demande}`, {
        ...demande,
        id_admin: adminId,
      });
      message.success('Demande updated successfully.');
      await fetchDemandes();
    } catch (error) {
      message.error('Failed to update demande.');
    }
  };

  const columns = [
    {
      title: 'Description',
      dataIndex: 'description',
      key: 'description',
    },
    {
      title: 'Status',
      dataIndex: 'status',
      key: 'status',
      render: (text, record) => (
        <Select
          value={record.status}
          onChange={(value) => handleStatusChange(record.id_demande, value)}
        >
          <Select.Option value="pending">Pending</Select.Option>
          <Select.Option value="in-progress">In Progress</Select.Option>
          <Select.Option value="completed">Completed</Select.Option>
        </Select>
      ),
    },
    {
      title: 'Coursier',
      dataIndex: 'id_coursier',
      key: 'id_coursier',
      render: (text, record) => (
        <Select
          value={record.id_coursier}
          onChange={(value) => handleCoursierChange(record.id_demande, value)}
        >
          {coursiers.map((coursier) => (
            <Select.Option key={coursier.id_coursier} value={coursier.id_coursier}>
              {coursier.nom} {coursier.prenom}
            </Select.Option>
          ))}
        </Select>
      ),
    },
    {
      title: 'Action',
      key: 'action',
      render: (text, record) => (
        <Button type="primary" onClick={() => handleUpdate(record)}>
          Update
        </Button>
      ),
    },
  ];

  return (
    <Layout style={{ minHeight: '100vh' }}>
      <Content style={{ margin: '24px 16px 0', overflow: 'initial' }}>
        <div style={{ padding: 24, background: '#fff', minHeight: '100vh' }}>
          <h1>Gestion Demande</h1>
          <Table dataSource={demandes} columns={columns} rowKey="id_demande" />
        </div>
      </Content>
    </Layout>
  );
};

export default AdminComponent;
