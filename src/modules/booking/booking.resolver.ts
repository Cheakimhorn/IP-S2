import { Args, Mutation, Query, Resolver } from '@nestjs/graphql';

@Resolver('Booking')
export class BookingResolver {
  private bookings = [
    {
      id: 1,
      start_date: '2025-06-10T12:00:00',
      end_date: '2025-06-12T12:00:00',
      hotel_id: 101,
      is_checked_in: false,
      price: 200,
    },
    {
      id: 2,
      start_date: '2025-06-15T12:00:00',
      end_date: '2025-06-18T12:00:00',
      hotel_id: 102,
      is_checked_in: false,
      price: 300,
    },
  ];

  // Fetch all bookings
  @Query('bookings')
  getAllBookings() {
    return this.bookings;
  }

  // Fetch bookings by start and end date
  @Query('bookingsByDateRange')
  getBookingsByDateRange(
    @Args('start_date') start_date: string,
    @Args('end_date') end_date: string
  ) {
    return this.bookings.filter(
      (booking) =>
        new Date(booking.start_date) >= new Date(start_date) &&
        new Date(booking.end_date) <= new Date(end_date)
    );
  }

  // Create a new booking
  @Mutation('createBooking')
  createBooking(
    @Args('start_date') start_date: string,
    @Args('end_date') end_date: string,
    @Args('hotel_id') hotel_id: number,
    @Args('price') price: number
  ) {
    const newBooking = {
      id: this.bookings.length + 1,
      start_date,
      end_date,
      hotel_id,
      is_checked_in: false,
      price,
    };
    this.bookings.push(newBooking);
    return newBooking;
  }

  // Cancel a booking
  @Mutation('cancelBooking')
  cancelBooking(@Args('id') id: number) {
    const bookingIndex = this.bookings.findIndex((booking) => booking.id === id);
    if (bookingIndex === -1) {
      throw new Error('Booking not found');
    }
    this.bookings.splice(bookingIndex, 1);
    return true;
  }

  // Check-in to a hotel
  @Mutation('checkIn')
  checkIn(@Args('id') id: number) {
    const booking = this.bookings.find((booking) => booking.id === id);
    if (!booking) {
      throw new Error('Booking not found');
    }
    booking.is_checked_in = true;
    return booking;
  }
}