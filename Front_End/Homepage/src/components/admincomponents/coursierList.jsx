import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Table, Button, Modal, Form, Input, InputNumber, Popconfirm } from 'antd';

const CoursierList = () => {
  const [coursiers, setCoursiers] = useState([]);
  const [selectedCoursier, setSelectedCoursier] = useState(null);
  const [updateData, setUpdateData] = useState({
    nom: '',
    prenom: '',
    tele: '',
    salaire: '',
    passwd: ''
  });
  const [isModalVisible, setIsModalVisible] = useState(false);

  useEffect(() => {
    const fetchCoursiers = async () => {
      try {
        const response = await axios.get('http://localhost:8000/api/courciers/all');
        setCoursiers(response.data);
      } catch (error) {
        console.error('Error fetching coursiers:', error);
      }
    };
    fetchCoursiers();
  }, []);

  const showUpdateModal = (coursier) => {
    setSelectedCoursier(coursier);
    setUpdateData({
      nom: coursier.name || '',
      prenom: coursier.lastname || '',
      tele: coursier.tele || '',
      salaire: coursier.salaire || '',
      passwd: ''
    });
    setIsModalVisible(true);
  };

  const handleUpdateChange = (e) => {
    const { name, value } = e.target;
    setUpdateData((prevData) => ({
      ...prevData,
      [name]: value
    }));
  };

  const handleUpdateSubmit = async () => {
    if (selectedCoursier) {
      try {
        await axios.put(`http://localhost:8000/api/courciers/${selectedCoursier.id}/update`, {
          nom: updateData.nom,
          prenom: updateData.prenom,
          tele: updateData.tele,
          salaire: updateData.salaire,
          passwd: updateData.passwd
        });

        const response = await axios.get('http://localhost:8000/api/courciers/all');
        setCoursiers(response.data);
        setIsModalVisible(false);
        setSelectedCoursier(null);
        setUpdateData({
          nom: '',
          prenom: '',
          tele: '',
          salaire: '',
          passwd: ''
        });
      } catch (error) {
        console.error('Error updating coursier:', error);
      }
    }
  };

  const handleDelete = async (id) => {
    try {
      await axios.delete(`http://localhost:8000/api/coursiers/${id}`);
      const response = await axios.get('http://localhost:8000/api/courciers/all');
      setCoursiers(response.data);
    } catch (error) {
      console.error('Error deleting coursier:', error);
    }
  };

  const columns = [
    {
      title: 'Nom',
      dataIndex: 'name',
      key: 'name',
    },
    {
      title: 'Prenom',
      dataIndex: 'lastname',
      key: 'lastname',
    },
    {
      title: 'Tele',
      dataIndex: 'tele',
      key: 'tele',
    },
    {
      title: 'Email',
      dataIndex: 'email',
      key: 'email',
    },
    {
      title: 'Salaire',
      dataIndex: 'salaire',
      key: 'salaire',
      render: (text) => <span>${text}</span>,
    },
    {
      title: 'Action',
      key: 'action',
      render: (_, record) => (
        <div>
          <Button onClick={() => showUpdateModal(record)} type="primary" style={{ marginRight: 8 }}>
            Update
          </Button>
          <Popconfirm
            title="Are you sure to delete this coursier?"
            onConfirm={() => handleDelete(record.id)}
            okText="Yes"
            cancelText="No"
          >
            <Button type="danger">Delete</Button>
          </Popconfirm>
        </div>
      ),
    },
  ];

  return (
    <div style={{ padding: '24px' }}>
      <h1>Coursier List</h1>
      <Table dataSource={coursiers} columns={columns} rowKey="id" />

      <Modal
        title="Update Coursier"
        visible={isModalVisible}
        onOk={handleUpdateSubmit}
        onCancel={() => setIsModalVisible(false)}
        okText="Submit"
        cancelText="Cancel"
      >
        <Form
          layout="vertical"
          initialValues={updateData}
          onValuesChange={(changedValues) => setUpdateData((prev) => ({ ...prev, ...changedValues }))}
        >
          <Form.Item label="Nom" name="nom">
            <Input name="nom" value={updateData.nom} onChange={handleUpdateChange} />
          </Form.Item>
          <Form.Item label="Prenom" name="prenom">
            <Input name="prenom" value={updateData.prenom} onChange={handleUpdateChange} />
          </Form.Item>
          <Form.Item label="Tele" name="tele">
            <Input name="tele" value={updateData.tele} onChange={handleUpdateChange} />
          </Form.Item>
          <Form.Item label="Salaire" name="salaire">
            <InputNumber
              name="salaire"
              value={updateData.salaire}
              onChange={(value) => setUpdateData((prev) => ({ ...prev, salaire: value }))}
              style={{ width: '100%' }}
            />
          </Form.Item>
          <Form.Item label="Password" name="passwd">
            <Input.Password name="passwd" value={updateData.passwd} onChange={handleUpdateChange} />
          </Form.Item>
        </Form>
      </Modal>
    </div>
  );
};

export default CoursierList;
