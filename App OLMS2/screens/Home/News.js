import React, { Component } from 'react'
import { Card, CardItem, Text, Button, Icon, Left, Body, ListItem, Thumbnail, Right, Grid } from 'native-base';
import {FlatList, StyleSheet, View, StackNavigator, TouchableOpacity } from 'react-native';
import Viewnews from './Viewnews';
import moment from 'moment'
export default class News extends React.Component {
  constructor(props){
    super(props);
  }
 
  render() {
    return (
        <FlatList data = {this.props.data} style={styles.backgroudList}
            renderItem={({item}) => (
                <ListItem onPress={ () => this.props.goSubject('SubjectScreen', {name: item.name, id: item.id, code: item.code})}>
                  <Body>
                  <Text>{item.name} ({item.code}) <Text note>{moment(item.created_at).fromNow()}</Text></Text>
                    <Text note>{item.comment}</Text>
                  </Body>
                </ListItem>
            )
          }
          keyExtractor={(item, index) => index}
        />
    )
  }
}

const styles = StyleSheet.create({
    backgroudList: {
      backgroundColor: 'white',
      margin: 5
    }
});