import React, { useState, useEffect } from 'react';
import { Table, Select, Button, message, Layout } from 'antd';
import axios from 'axios';

const { Content } = Layout;

const AdminComponent = () => {
  const [demandes, setDemandes] = useState([]);
  const [coursierEmails, setCoursierEmails] = useState([]);

  const fetchDemandes = async () => {
    try {
      const response = await axios.get('http://127.0.0.1:8000/api/demandes/finddemandes/newdemandes');
      setDemandes(response.data);
    } catch (error) {
      message.error('Failed to fetch demandes.');
    }
  };

  const fetchCoursierEmails = async () => {
    try {
      const response = await axios.get('http://127.0.0.1:8000/api/courciers/all/emails');
      setCoursierEmails(response.data);
    } catch (error) {
      message.error('Failed to fetch coursiers.');
    }
  };

  useEffect(() => {
    fetchDemandes();
    fetchCoursierEmails();
  }, []);

  const handleStatusChange = (id, value) => {
    const updatedDemandes = demandes.map((demande) =>
      demande.id_demande === id ? { ...demande, status: value, coursier_email: value === 'refuser' ? null : demande.coursier_email } : demande
    );
    setDemandes(updatedDemandes);
  };

  const handleCoursierEmailChange = (id, value) => {
    const updatedDemandes = demandes.map((demande) =>
      demande.id_demande === id ? { ...demande, coursier_email: value } : demande
    );
    setDemandes(updatedDemandes);
  };

  const handleUpdate = async (demande) => {
    const adminId = localStorage.getItem('clientId');
    try {
      await axios.put(`http://127.0.0.1:8000/api/demandes/updatedemandestatus/${demande.id_demande}`, {
        status: demande.status,
        admin_id: adminId,
        coursier_email: demande.status === 'accepter' ? demande.coursier_email : null,
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
          style={{ width: 150 }}
        >
          <Select.Option value="accepter">Accepter</Select.Option>
          <Select.Option value="refuser">Refuser</Select.Option>
        </Select>
      ),
    },
    {
      title: 'Coursier Email',
      dataIndex: 'coursier_email',
      key: 'coursier_email',
      render: (text, record) => (
        <Select
          value={record.coursier_email}
          onChange={(value) => handleCoursierEmailChange(record.id_demande, value)}
          disabled={record.status === 'refuser'}
          style={{ width: 250 }} // Adjust the width as needed
        >
          {coursierEmails.map((email) => (
            <Select.Option key={email} value={email}>
              {email}
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
