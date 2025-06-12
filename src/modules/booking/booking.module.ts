import { Module } from '@nestjs/common';
import { BookingResolver } from './booking.resolver';

@Module({
  imports: [],
  controllers: [],
  providers: [BookingResolver],
})
export class BookingModule {}